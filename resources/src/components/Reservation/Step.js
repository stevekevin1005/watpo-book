import { Link } from 'react-router-dom';
import { translate } from 'react-i18next';
import Steps from './Steps';
import CheckService from './CheckService';
import CheckTime from './CheckTime';
import CheckDetail from './CheckDetail';

const Col = ReactBootstrap.Col;

class Step extends React.Component{
    constructor(props){
        super(props);
        this.nextStep = this.nextStep.bind(this);
    }
    nextStep(){
        this.props.history.push('/reservation/' + (+this.props.match.params.step + 1));
    }
    componentDidUpdate(prevProps, prevState){
        // 1. 轉頁
        // 2. 若返回上一步/回到先前的步驟，清除已填寫的資料
        if(isNaN(+this.props.match.params.step) || +this.props.match.params.step > 2 || +this.props.match.params.step < 0) location.href = '../reservation/0';
        switch(+this.props.match.params.step){
            case 0:
                if(this.props.reservation.roomId && this.props.reservation.name && this.props.reservation.contactNumber && this.props.reservation.operator && this.props.reservation.guestNum) this.props.clearData(+this.props.match.params.step);
                if( this.props.sourceData.room)  this.props.clearSourceData("room");
                if(this.props.sourceData.service_provider_list)  this.props.clearSourceData("service_provider_list");
                break;
            case 1:
                if(!this.props.reservation.shop || !this.props.reservation.service) location.href = '../reservation/0';
                if(this.props.reservation.time && this.props.reservation.date) this.props.clearData(+this.props.match.params.step);
                if(this.props.sourceData.timeList) this.props.clearSourceData("timeList");
                break;
            case 2:
                if(!this.props.reservation.shop || !this.props.reservation.service || !this.props.reservation.guestNum || !this.props.reservation.roomId || !this.props.reservation.operator || !this.props.reservation.name || !this.props.reservation.contactNumber) location.href = '../reservation/0';
                break;
        }
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
            case 2:
                el = <CheckTime {...this.props} nextStep={this.nextStep}/>;
                break;
            case 1:
                el = <CheckDetail {...this.props} nextStep={this.nextStep}/>;
                break;
            default:
                el = null;
        }
        return (<div>
            <Steps step={currentStep}/>
            {currentStep > 0 && 
            <Col md="12"><p className="prevStap"><Link to={"/reservation/"+ (currentStep - 1)}><span><i className="fa fa-angle-left" aria-hidden="true"></i>{" "+t("prevStep")}</span></Link></p></Col>
            }
            {el}
        </div>);
    }
}

module.exports = translate()(Step);