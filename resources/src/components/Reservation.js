// 負責渲染和清理資料

import CheckDetail from "./Reservation/CheckDetail";
import CheckService from "./Reservation/CheckService";
import CheckTime from "./Reservation/CheckTime";
import { Link } from 'react-router-dom';
import { translate } from 'react-i18next';
import {connect} from "react-redux";
import {bindActionCreators} from "redux";
import LoadingAnimation from "./LoadingAnimation";
import clearReservation from "../dispatchers/clearReservation";
import clearSourceData from "../dispatchers/clearSourceData";

const Grid = ReactBootstrap.Grid,
    Row = ReactBootstrap.Row,
    Col = ReactBootstrap.Col,
    Button = ReactBootstrap.Button;
import SweetAlert from 'sweetalert-react';

class Reservation extends React.Component{
    constructor(props){
        super(props);

        this.state = {
            showAlert: false,
            alertTitle: "",
            alertText: "",
            success: false
        };
        this.send = this.send.bind(this);
    }
    componentWillReceiveProps(nextProps){

        // 清資料
        const reservation = this.props.reservation;
        switch(nextProps.match.params.step){
            case "0":
                // step1 => step0
                if(reservation.time!=undefined || reservation.date!=undefined){
                    this.props.clearReservation("step1");
                    this.props.clearSourceData("selectedDetail");
                    this.props.clearSourceData("timeList");
                    if(((reservation.operator.length>2 || reservation.room!=undefined) || (reservation.guestNum > 1 ||reservation.name!=undefined)) || reservation.contactNumber!=undefined){
                        this.props.clearReservation("step2");
                    } 
                }
                break;
            case "1":
                // step2 => step1
                if(((reservation.operator.length>2 || reservation.room!=undefined) || (reservation.guestNum > 1 ||reservation.name!=undefined)) || reservation.contactNumber!=undefined){
                    this.props.clearReservation("step2");
                } 
                break;
            case "2":
                if(nextProps.sourceData.timeList === undefined || nextProps.sourceData.selectedDetail === undefined)
                    this.props.history.push('/reservation/0');
        }
    }
    send(event){
        event.preventDefault();
        // check if there are repeated operators
        for(let i =0;i<this.props.reservation.operator.length;i++){
            if(this.props.reservation.operator.indexOf(this.props.reservation.operator[i],i+1)>0){
                this.setState({
                    showAlert: true,
                    alertTitle: "錯誤",
                    alertText: "指定的師傅編號不可重複"
                });
                return;
            }
        }

        // get end time
        const duration = this.props.sourceData.services[this.props.reservation.service].time / 60,
              token = document.querySelector('input[name="_token"]').value,
              that = this;
        let endTime = this.props.reservation.time.split(":");
        endTime[0] = parseInt(endTime[0]) + duration;
        endTime = (endTime[0]>=10?endTime[0]:"0"+endTime[0]) + ":" + endTime[1] + ":" + endTime[2];

        // get info: service, shop
        const reservation = this.props.reservation,
              serviceName = this.props.sourceData.services[reservation.service].title;

        // call API
        axios({
            method: "post",
            url: "/api/order",
            params: {
                phone: reservation.contactNumber,
                shop_id: this.props.sourceData.shops[reservation.shop].id,
                service_id: this.props.sourceData.services[reservation.service].id,
                start_time: reservation.date + " " + reservation.time,
                end_time: reservation.date + " " + endTime,
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
                that.setState({
                    success: true,
                    showAlert: true,
                    alertTitle: "預定成功",
                    alertText: reservation.name + " " + reservation.date + " " + reservation.time + " 預約 " + serviceName+ " 服務 " + reservation.guestNum + " 人 成功"
                });
            }else{
                // show failure alert
                that.setState({
                    showAlert: true,
                    alertTitle: "錯誤",
                    alertText: "系統錯誤請再重試"
                });
            }
        }).catch(function(error){
            console.log(error);
            // error handle
            that.setState({
                showAlert: true,
                alertTitle: "錯誤",
                alertText: "系統錯誤請再重試"
            });
        });
    }
    render(){
        const { t } = this.props;

        // set up steps
        const currentStep = parseInt(this.props.match.params.step),
              stepsData = [t("chooseService"),t("chooseTime"),t("checkDetails")], pointer = {cursor: "pointer"}, currentStepStyle = {cursor:"pointer",color: "#914327"};

        let steps = stepsData.map((step, index,arr)=>{
                let divider = index < arr.length - 1 && <span> <i className="fa fa-angle-right" aria-hidden="true"></i> </span>;
                if(currentStep > index)return (
                    <span key={index}>
                        <Link to={"/reservation/"+index}>
                            <span 
                                style = {currentStep === index?currentStepStyle: null}
                            >{step}</span>
                        </Link>
                    {divider}</span>);
                return (
                    <span>
                            <span 
                                style = {currentStep === index?currentStepStyle: null}
                            >{step}</span>
                    {divider}</span>);
              });
        
        // set up button
        const reservation = this.props.reservation;
        let isDisabled = false, button;
        switch(currentStep){
            case 0:
                if(reservation.shop === undefined || reservation.service === undefined) isDisabled = true;
                break;
            case 1:
                if(!reservation.date || !reservation.time) isDisabled = true;
                break;
            case 2:
                if(((!reservation.operator || !reservation.room) || ( reservation.guestNum  === undefined || reservation.name === undefined)) || (!reservation.contactNumber||reservation.contactNumber.length<6)) isDisabled = true;
                break;
        }
        if(isDisabled){
            button = (<Button bsStyle="primary" bsSize="large" disabled={isDisabled}>
                {currentStep==2?t("send"):t("nextStep")}
            </Button>);
        }else if(currentStep == 2){
            button = (<Button bsStyle="primary" bsSize="large" onClick={this.send}>
                {t("send")}
            </Button>);
        }else{
            button = (<Link to = {"/reservation/"+(currentStep + 1)}>
            <Button bsStyle="primary" bsSize="large">
                {t("nextStep")}
            </Button>
            </Link>);
        }

        // content to show
        let el;
        switch(currentStep){
            case 0:
                el = (
                    <CheckService/>);
                break;
            case 1:
                el = <CheckTime/>;
                break;
            case 2:
                el = <CheckDetail/>;
                break;
            default:
                return;
        }

        return(
            <Grid>
            <div className="reservationContainer">
                <Row className="reservationGrid">
                <Col md={12}>
                    <div className="steps">
                        {steps}
                    </div>
                </Col>
                {currentStep > 0 && <Col md={12} >
                <p className="prevStap"><Link to={"/reservation/"+ (currentStep - 1)}><span><i className="fa fa-angle-left" aria-hidden="true"></i>{" "+t("prevStep")}</span></Link></p>
                </Col>}
                    <div className="reservationContent" style={{padding:"16px 0"}}>
                        {el}
                    </div>    
                <Col md={12}>
                    {button}
                </Col>
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
        loading: state.loading,
        reservation: state.reservation,
        sourceData: state.sourceData
    }
}

const mapDispatchToProps = (dispatch)=>{
    return bindActionCreators({
        clearReservation: clearReservation,
        clearSourceData: clearSourceData
    },dispatch);
}
  
Reservation = connect(mapStateToProps, mapDispatchToProps)(Reservation);

module.exports = translate()(Reservation);