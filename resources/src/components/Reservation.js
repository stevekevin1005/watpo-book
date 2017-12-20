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

class Reservation extends React.Component{
    constructor(props){
        super(props);
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
                }
                break;
            case "1":
                // step2 => step1
                if(((reservation.operator!=undefined || reservation.room!=undefined) || (reservation.guestNum!=undefined ||reservation.name!=undefined)) || reservation.contactNumber!=undefined){
                    this.props.clearReservation("step2");
                } 
                break;
            case "2":
                if(nextProps.sourceData.timeList === undefined || nextProps.sourceData.selectedDetail === undefined)
                    this.props.history.push('/reservation/0');
        }
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
                if(!reservation.shop || !reservation.service) isDisabled = true;
                break;
            case 1:
                if(!reservation.date || !reservation.time) isDisabled = true;
                break;
            case 2:
                if(((!reservation.operator || !reservation.room) || ( reservation.guestNum || reservation.name)) || reservation.contactNumber) isDisabled = true;
                break;
        }
        if(isDisabled){
            button = (<Button bsStyle="primary" bsSize="large" disabled={isDisabled}>
                {currentStep==2?t("send"):t("nextStep")}
            </Button>);
        }else if(currentStep == 2){
            button = (<Button bsStyle="primary" bsSize="large">
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