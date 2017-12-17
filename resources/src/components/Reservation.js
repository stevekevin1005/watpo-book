// 負責渲染和清理資料

import CheckDetail from "./Reservation/CheckDetail";
import CheckService from "./Reservation/CheckService";
import CheckTime from "./Reservation/CheckTime";
import { Link } from 'react-router-dom';
import { translate } from 'react-i18next';
import {connect} from "react-redux";

const Grid = ReactBootstrap.Grid,
    Row = ReactBootstrap.Row,
    Col = ReactBootstrap.Col,
    Button = ReactBootstrap.Button;

class Reservation extends React.Component{
    constructor(props){
        super(props);
    }
    componentDidReceiveProps(nextProps){
        // 清資料或發請求
        switch(nextProps){

        }
    }
    render(){
        const { t } = this.props;

        // steps
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

        // forms to show
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
            case 3: 
                return;
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
                    {currentStep < 2?<Link to = {"/reservation/"+(currentStep + 1)}>
                    <Button bsStyle="primary" bsSize="large" onClick={this.next}>
                        {t("nextStep")}
                    </Button>
                    </Link>:
                    <Button bsStyle="primary" bsSize="large" onClick={this.next}>
                        {t("send")}
                    </Button>
                    }
                </Col>
                </Row>                
                </div>
            </Grid>
            
        );
    }
}


module.exports = translate()(Reservation);