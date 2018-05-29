import { translate } from 'react-i18next';
import { Link } from 'react-router-dom';

const Col = ReactBootstrap.Col;

const Steps = props=>{
    const { t } = props;

    // set up steps
    const currentStep = +(props.step),
          stepsData = [t("chooseService"),t("checkDetails"),t("chooseTime")], pointer = {cursor: "pointer"}, currentStepStyle = {cursor:"pointer",color: "#914327"};
    
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
                    <span key={index}>
                        <span 
                            style = {currentStep === index?currentStepStyle: null}
                        >{step}</span>
                    {divider}</span>);
        });

        return (
            <Col md={12}>
                <div className="steps">
                    {steps}
                </div>
            </Col>
            );
}

module.exports = translate()(Steps);