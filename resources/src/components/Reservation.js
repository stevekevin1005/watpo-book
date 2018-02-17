// 負責渲染和清理資料

import Steps from "./Reservation/Steps";
import Step from "./Reservation/Step";
import CheckDetail from "./Reservation/checkDetail";
import CheckService from "./Reservation/checkService";
import CheckTime from "./Reservation/checkTime";
import { Link, Route } from 'react-router-dom';
import { translate } from 'react-i18next';

import {connect} from "react-redux";
import {bindActionCreators} from "redux";
import LoadingAnimation from "./LoadingAnimation";
import toggleLoading from "../dispatchers/toggleLoading";
import clearCheckOrdersInfo from "../dispatchers/clearCheckOrdersInfo";

const Grid = ReactBootstrap.Grid,
    Row = ReactBootstrap.Row,
    Col = ReactBootstrap.Col;
import SweetAlert from 'sweetalert-react';

class Reservation extends React.Component{
    constructor(props){
        super(props);

        this.state = {
            showAlert: false,
            alertTitle: "",
            alertText: "",
            success: false,

            reservation: {
                shop: null,
                service: null,

                guestNum: 0,
                shower: null,
                operator: [],
                roomId: null,
                name: null,
                contactNumber: null,

                date: null,
                time: null
            },

            sourceData: {
                shops: null,
                services: null,
                timeList: null,
                service_provider_list: null,
                room: null
            }
        };

        this.saveReservationAndSourceData = this.saveReservationAndSourceData.bind(this);
        this.clearData = this.clearData.bind(this);
        this.clearSourceData = this.clearSourceData.bind(this);
        this.send = this.send.bind(this);
        this.showErrorPopUp = this.showErrorPopUp.bind(this);
    }
    componentDidMount(){
        if(this.props.checkOrdersInfo != {}){
            this.props.clearCheckOrdersInfo("name");
            this.props.clearCheckOrdersInfo("contactNumber");
        }

            const that = this,
                  csrf_token = document.querySelector('input[name="_token"]').value;    
            let finished = 0;

            this.props.toggleLoading(true);
    
            axios({
                method: "get",
                url: "../api/shop_list",
                headers: {'X-CSRF-TOKEN': csrf_token},
                responseType: 'json'
            })
            .then(function (response) {
                if(response.statusText == "OK"){
                    let sourceData = JSON.parse(JSON.stringify(that.state.sourceData));
                    sourceData["shops"] = response.data;
                    that.setState({sourceData});
                    finished += 1;
                    if(finished == 2) that.props.toggleLoading(false);;
                }
            })
            .catch(function (error) {
                console.log(error);
                if(finished == 2) that.props.toggleLoading(false);
                that.showErrorPopUp();
            });

            axios({
                method: "get",
                url: "../api/service_list",
                responseType: 'json',
                headers: {'X-CSRF-TOKEN': csrf_token}
            })
            .then(function (response) {
                if(response.statusText == "OK"){
                    let sourceData = JSON.parse(JSON.stringify(that.state.sourceData));
                    sourceData["services"] = response.data;
                    that.setState({sourceData});
                    finished += 1;
                    if(finished == 2) that.props.toggleLoading(false);
                }
            })
            .catch(function (error) {
                console.log(error);
                if(finished == 2) that.props.toggleLoading(false);
                that.showErrorPopUp();
            });
    }
    clearSourceData(key){
        this.props.toggleLoading(true);
        const sourceData = JSON.parse(JSON.stringify(this.state.sourceData));
        sourceData[key] = null;
        this.setState({
            sourceData
        }, this.props.toggleLoading(false));
    }
    saveReservationAndSourceData(newReservationData, newSourceData, callback){
        const reservation = JSON.parse(JSON.stringify(this.state.reservation)),
              sourceData = JSON.parse(JSON.stringify(this.state.sourceData));

        if(newReservationData){
            Object.keys(newReservationData).forEach((key,i)=>{
                reservation[key] = newReservationData[key];
            });
        }
        if(newSourceData){
            Object.keys(newSourceData).forEach((key,i)=>{
                sourceData[key] = newSourceData[key];
            });
        }

        this.props.toggleLoading(true);
        this.setState({
            reservation,
            sourceData
        },()=>{
            if(callback) callback();
            this.props.toggleLoading(false);
        });
    }
    clearData(step){
        let sourceData = JSON.parse(JSON.stringify(this.state.sourceData)),
            reservation = JSON.parse(JSON.stringify(this.state.reservation));
        
        switch(step){
            case 0:
                this.props.toggleLoading(true);
                sourceData["service_provider_list"] = null;
                sourceData["room"] = null;
                const newReservationData = {
                    service: reservation.service,
                    shop: reservation.shop,
                    
                    guestNum: null,
                    shower: null,
                    operator: [],
                    roomId: null,
                    name: null,
                    contactNumber: null,

                    date: null,
                    time: null
                };
                this.setState({ 
                    sourceData, 
                    reservation: newReservationData
                }, this.props.toggleLoading.bind(null, false));
                break;
            case 1:
                this.props.toggleLoading(true);
                reservation["date"] = null;
                reservation["time"] = null;
                sourceData["timeList"] = null;
                this.setState({ sourceData, reservation}, this.props.toggleLoading.bind(null, false));
                break;
            case 2:
        }
    }
    send(){
        const { t } = this.props;
      
        // get info: service, shop
        const reservation = this.state.reservation,
              serviceIndex = this.state.sourceData.services.reduce((result, service, index)=>{return result + (service.id == reservation.service? index:0)}, 0),
              serviceName = this.state.sourceData.services[serviceIndex].title;

        // get end time
        const duration = this.state.sourceData.services[serviceIndex].time / 60,
            token = document.querySelector('input[name="_token"]').value,
            that = this;
        let endTime = reservation.time.split(":");
            endTime[0] = parseInt(endTime[0]) + duration;
            endTime = (endTime[0]>=10?endTime[0]:"0"+endTime[0]) + ":" + endTime[1] + ":" + endTime[2];
        let date = reservation.date;

        // 確認是否需要將日期改為隔日
        if(reservation.time[0]==="0"){
                // 是過凌晨00:00:00的時間，故調整日期
                let newDate = date.split("/").map((val)=>{
                    return parseInt(val);
                }),
                daynum = new Date(newDate[0], newDate[1], 0).getDate(); //該月最後一天日期
                //跨月
                if(newDate[2]==daynum){
                    newDate[1]=newDate[1]+1;
                    newDate[2]=1;
                }
                //跨年
                else if(newDate[1]==12&&newDate[2]==31){
                    newDate[0]=newDate[0]+1;
                    newDate[1]=1;
                    newDate[2]=1;
                }else{
                    newDate[2]=newDate[2]+1;
                }
                if(newDate[1] < 10) newDate[1] = "0"+newDate[1];
                if(newDate[2] < 10) newDate[2] = "0"+newDate[2];
                newDate = newDate.join("/");
                date = newDate;
        }

        // call API
        this.props.toggleLoading(true);
        axios({
            method: "post",
            url: "/api/order",
            params: {
                phone: reservation.contactNumber,
                shop_id: reservation.shop,
                service_id: reservation.service,
                start_time: date + " " + reservation.time,
                end_time: date + " " + endTime,
                room_id: reservation.roomId,
                person: reservation.guestNum,
                service_provider_id: reservation.operator.join(),
                name: reservation.name
            },
            headers: {'X-CSRF-TOKEN': token},
            responseType: 'json'
        }).then(function(response){
            if(response.statusText == "OK"){
                // show success alert
                that.props.toggleLoading(false);
                that.setState({
                    success: true,
                    showAlert: true,
                    alertTitle: t("reserveSuccess"),
                    alertText: t("reservatorName") + ": " +reservation.name + "\n" + t("reservatorDate") + ": "+reservation.date + " " + reservation.time + "\n服務: " + serviceName + "\n人數: " + reservation.guestNum +" " + (reservation.guestNum>1?t("people"):t("person"))+ "\n" + t("reserveNotice")
                });
            }else{
                // show failure alert
                that.props.toggleLoading(false);
                that.setState({
                    showAlert: true,
                    alertTitle: t("error"),
                    alertText: t("errorHint_system")
                });
            }
        }).catch(function(error){
            console.log(error);
            // error handle
            that.props.toggleLoading(false);
            that.showErrorPopUp();
        });
    }
    showErrorPopUp(){
        const { t } = this.props;
        this.setState({
            showAlert: true,
            alertTitle: t("error"),
            alertText: t("errorHint_system")
        });
    }
    render(){
        const Step_ = props => {
            return (<Step 
                        {...props} 
                        saveReservationAndSourceData = {this.saveReservationAndSourceData}
                        clearSourceData = {this.clearSourceData}
                        send = {this.send}
                        clearData = {this.clearData}
                        showErrorPopUp = {this.showErrorPopUp}

                        reservation = {this.state.reservation}
                        sourceData = {this.state.sourceData}
                    />);
        }

        return(
            <Grid>
                <div className="reservationContainer">
                    <Row className="reservationGrid">
                        <div className="reservationContent" style={{padding:"16px 0"}}>
                            <Route path="/reservation/:step" component={Step_}/>
                        </div>    
                    {this.props.loading && <Col md={12}><LoadingAnimation /></Col>}
                    </Row>                
                </div>
                <SweetAlert
                    show={this.state.showAlert}
                    title={this.state.alertTitle}
                    text={this.state.alertText}
                    onConfirm={() => {
                        this.setState({ showAlert: false });
                        if(this.state.success) location.reload();
                    }}
                />
            </Grid>
        );
    }
}

const mapStateToProps = (state)=>{
    return {
        checkOrdersInfo: state.checkOrdersInfo,
        loading: state.loading
    }
}

const mapDispatchToProps = (dispatch)=>{
    return bindActionCreators({
        clearCheckOrdersInfo: clearCheckOrdersInfo,
        toggleLoading: toggleLoading
    },dispatch);
}
  
Reservation = connect(mapStateToProps, mapDispatchToProps)(Reservation);

module.exports = translate()(Reservation);