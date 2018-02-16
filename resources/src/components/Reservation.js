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
import clearReservation from "../dispatchers/clearReservation";
import clearSourceData from "../dispatchers/clearSourceData";
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
                shop: 0,
                service: 0,

                guestNum: 0,
                shower: false,
                operator: null,

                name: null,
                number: null,

                date: null,
                time: null
            },

            sourceData: {
                shops: null,
                services: null,
                timeList: null,
                selectedDetail: null
            },

            loading: false
        };

        this.setReservation = this.setReservation.bind(this);
        this.setSourceData = this.setSourceData.bind(this);
        this.clearSourceData = this.clearSourceData.bind(this);
        this.toggleLoading = this.toggleLoading.bind(this);
        this.send = this.send.bind(this);

        this.getStep1SourceData = this.getStep1SourceData.bind(this);
    }
    // componentDidMount(){
    //     if(this.props.checkOrdersInfo != {}){
    //         this.props.clearCheckOrdersInfo("name");
    //         this.props.clearCheckOrdersInfo("contactNumber");
    //     }
    // }
    componentDidMount(){
        this.getStep1SourceData();
    }
    getStep1SourceData(){
            // get data of shops and services
            const sourceData = this.state.sourceData;
    
            // if not, fetch data of shops and services
            const that = this,
                  csrf_token = document.querySelector('input[name="_token"]').value;
            let finished = 0;
            
            if(!sourceData.shops){
                this.toggleLoading();
    
                axios({
                    method: "get",
                    url: "../api/shop_list",
                    headers: {'X-CSRF-TOKEN': csrf_token},
                    responseType: 'json'
                })
                .then(function (response) {
                    if(response.statusText == "OK"){
                        that.setSourceData("shops", response.data);
    
                        if(finished == 2) this.toggleLoading();;
                    }
                })
                .catch(function (error) {
                    console.log(error);
    
                    if(finished == 2) this.toggleLoading();;
                });
            }else{
                finished += 1;
            }
    
            if(!sourceData.services){
                this.toggleLoading();
    
                axios({
                    method: "get",
                    url: "../api/service_list",
                    responseType: 'json',
                    headers: {'X-CSRF-TOKEN': csrf_token}
                })
                .then(function (response) {
                    if(response.statusText == "OK"){
                        that.setSourceData("services", response.data);
                        finished += 1;
                        if(finished == 2) this.toggleLoading();;
                    }
                })
                .catch(function (error) {
                    console.log(error);
    
                    if(finished == 2) this.toggleLoading();;
                });
            }else{
                finished += 1;
            }
    }
    setSourceData(key, value){
        const sourceData = JSON.parse(JSON.stringify(this.state.sourceData));
        sourceData[key] = value;
        this.setState({
            sourceData
        });
    }
    clearSourceData(key){
        const sourceData = JSON.parse(JSON.stringify(this.state.sourceData));
        sourceData[key] = null;
        this.setState({
            sourceData
        });
    }
    setReservation(key, value){
        const reservation = JSON.parse(JSON.stringify(this.state.reservation));
        reservation[key] = value;
        this.setState({
            reservation
        });
    }
    toggleLoading(){
        this.setState(prevState=>{
            return {loading: !prevState.loading};
        });
    }
    send(){
        const { t } = this.props;
      
      // get info: service, shop
      const reservation = this.props.reservation,
            serviceName = this.props.sourceData.services[reservation.service].title;

      // check if there's available room
      if(reservation.room === undefined){
        that.setState({
            showAlert: true,
            alertTitle: t("error"),
            alertText: t("errorHint_noRoom")
        });
        return;
      }
    // get end time
    const duration = this.props.sourceData.services[this.props.reservation.service].time / 60,
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
      // console.log(date);
      // call API
      this.props.toggleLoading(true);
      axios({
          method: "post",
          url: "/api/order",
          params: {
              phone: reservation.contactNumber,
              shop_id: this.props.sourceData.shops[reservation.shop].id,
              service_id: this.props.sourceData.services[reservation.service].id,
              start_time: date + " " + reservation.time,
              end_time: date + " " + endTime,
              room_id: reservation.room,
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
          that.setState({
              showAlert: true,
              alertTitle: t("error"),
              alertText: t("errorHint_system")
          });
      });
    }
    render(){
        const Step_ = props => {
            return (<Step 
                        {...props} 
                        setReservation = {this.setReservation}
                        clearSourceData = {this.clearSourceData}
                        setSourceData = {this.setSourceData}
                        toggleLoading = {this.toggleLoading}
                        send = {this.send}

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
                {this.state.loading && <Col md={12}><LoadingAnimation /></Col>}
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

// const mapStateToProps = (state)=>{
//     return {
//         checkOrdersInfo: state.checkOrdersInfo
//     }
// }

// const mapDispatchToProps = (dispatch)=>{
//     return bindActionCreators({
//         clearCheckOrdersInfo: clearCheckOrdersInfo
//     },dispatch);
// }
  
// Reservation = connect(mapStateToProps, mapDispatchToProps)(Reservation);

module.exports = translate()(Reservation);