import { Link } from 'react-router-dom';
import { translate } from 'react-i18next';
import {connect} from "react-redux";
import LoadingAnimation from "./LoadingAnimation";

import CheckInformation from "./BookCheck/CheckInformation";
import CheckTime from "./Reservation/checkTime";

const Grid = ReactBootstrap.Grid,
    Row = ReactBootstrap.Row,
    Col = ReactBootstrap.Col;

class BookCheck extends React.Component{
	constructor(props){
    super(props);
  }

  render(){
  	const { t } = this.props;
  	// set up steps
    const currentStep = parseInt(this.props.match.params.step),
          stepsData = [t("input information"),t("check book")], pointer = {cursor: "pointer"}, currentStepStyle = {cursor:"pointer",color: "#914327"};

    let steps = stepsData.map((step, index,arr)=>{
      let divider = index < arr.length - 1 && <span> <i className="fa fa-angle-right" aria-hidden="true"></i> </span>;
      if(currentStep > index)return (
          <span key={index}>
              <Link to={"/book/check/"+index}>
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

		let el;
    switch(currentStep){
        case 0:
            el = (
                <CheckInformation nextStep={this.nextStep}/>);
            break;
        case 1:
            el = <CheckTime nextStep={this.nextStep}/>;
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
                {this.props.loading && <Col md={12}><LoadingAnimation /></Col>}
                </Row>                
                </div>
            </Grid>
        );
    }
}

BookCheck = connect()(BookCheck);

module.exports = translate()(BookCheck);