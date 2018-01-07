// 負責渲染和清理資料

import OrderInfos from "./CheckOrders/OrderInfos";
import InputInfo from "./CheckOrders/InputInfo";
import { Link } from 'react-router-dom';
import { translate } from 'react-i18next';
import {connect} from "react-redux";
import {bindActionCreators} from "redux";
import LoadingAnimation from "./LoadingAnimation";
import toggleLoading from "../dispatchers/toggleLoading";
import clearReservation from "../dispatchers/clearReservation";
import clearSourceData from "../dispatchers/clearSourceData";

const Grid = ReactBootstrap.Grid,
    Row = ReactBootstrap.Row,
    Col = ReactBootstrap.Col;
import SweetAlert from 'sweetalert-react';

class CheckOrders extends React.Component{
    constructor(props){
        super(props);

        this.state = {
            showAlert: false,
            alertTitle: "",
            alertText: ""
        };
        
        this.nextStep = this.nextStep.bind(this);
        this.getOrdersError = this.getOrdersError.bind(this);
        this.cancelSuccess = this.cancelSuccess.bind(this);
    }
    componentDidMount(){
        if(this.props.reservation !== null){
            this.props.clearReservation("all");
            this.props.clearSourceData("timeList");
            this.props.clearSourceData("selectedDetail");
        }
    }
    nextStep(){
        this.props.history.push('/checkOrders/' + (parseInt(this.props.match.params.step) + 1));
    }
    getOrdersError(){
        this.setState({
            showAlert: true,
            alertTitle: "Error",
            alertText: "errorHint_system"
        });
    }
    cancelSuccess(){
        this.setState({
            showAlert: true,
            alertTitle: "success",
            alertText: "orderCanceled"
        });
    }
    render(){
        const { t } = this.props;

        // set up steps
        const currentStep = parseInt(this.props.match.params.step),
              stepsData = [t("inputRelatedInfo"),t("orderInfo")], pointer = {cursor: "pointer"}, currentStepStyle = {cursor:"pointer",color: "#914327"};

        let steps = stepsData.map((step, index,arr)=>{
                let divider = index < arr.length - 1 && <span> <i className="fa fa-angle-right" aria-hidden="true"></i> </span>;
                if(currentStep > index)return (
                    <span key={index}>
                        <Link to={"/checkOrders/"+index}>
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

        // content to show
        let el;
        switch(currentStep){
            case 0:
                el = (
                    <InputInfo nextStep={this.nextStep}/>);
                break;
            case 1:
                el = <OrderInfos getOrdersError={this.getOrdersError} cancelSuccess={this.cancelSuccess}/>;
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
                <p className="prevStap"><Link to={"/checkOrders/"+ (currentStep - 1)}><span><i className="fa fa-angle-left" aria-hidden="true"></i>{" "+t("prevStep")}</span></Link></p>
                </Col>}
                    <div className="reservationContent" style={{padding:"16px 0"}}>
                        {el}
                    </div>    
                {this.props.loading && <Col md={12}><LoadingAnimation /></Col>}
                </Row>                
                </div>
                <SweetAlert
                    show={this.state.showAlert}
                    title={t(this.state.alertTitle)}
                    text={t(this.state.alertText)}
                    onConfirm={() => {
                        this.setState({ showAlert: false });
                        if(this.alertTitle == "Error"){ location.href="../checkOrders/0" }
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
        checkOrdersInfo: state.checkOrdersInfo
    }
}

const mapDispatchToProps = (dispatch)=>{
    return bindActionCreators({
        toggleLoading: toggleLoading,
        clearReservation: clearReservation,
        clearSourceData: clearSourceData
    },dispatch);
}
  
CheckOrders = connect(mapStateToProps, mapDispatchToProps)(CheckOrders);

module.exports = translate()(CheckOrders);