import { Link } from 'react-router-dom';
import { translate } from 'react-i18next';
import Steps from './Steps';
import CheckService from './CheckService';
import CheckTime from './CheckTime';
import CheckDetail from './CheckDetail';

class Step extends React.Component{
    constructor(props){
        super(props);
        this.nextStep = this.nextStep.bind(this);
    }
    nextStep(){
        this.props.history.push('/reservation/' + (+this.props.match.params.step + 1));
    }
    render(){
        const { t } = this.props;

        const currentStep = +this.props.match.params.step;
        let el;
        // content to show
        switch(currentStep){
            case 0:
                el = <CheckService {...this.props} nextStep={this.nextStep}/>;
                break;
            case 1:
                el = <CheckTime {...this.props} nextStep={this.nextStep}/>;
                break;
            case 2:
                el = <CheckDetail {...this.props}/>;
                break;
            default:
                el = null;
        }
        return (<div>
            <div className="steps">
                <Steps step={currentStep}/>
            </div>
            {currentStep > 0 && 
            <p className="prevStap"><Link to={"/reservation/"+ (currentStep - 1)}><span><i className="fa fa-angle-left" aria-hidden="true"></i>{" "+t("prevStep")}</span></Link></p>
            }
            {el}
        </div>);
    }
}

module.exports = translate()(Step);