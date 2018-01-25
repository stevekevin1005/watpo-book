// 負責寫資料(師傅,人數,房號,姓名,電話)到global state
import { translate } from 'react-i18next';
import {connect} from "react-redux";
import {bindActionCreators} from "redux";
import Button from "./Button";
import toggleLoading from "../../dispatchers/toggleLoading";
import setCheckOrdersInfo from "../../dispatchers/setCheckOrdersInfo";

const Grid = ReactBootstrap.Grid,
    Row = ReactBootstrap.Row,
    Col = ReactBootstrap.Col,
    FormGroup = ReactBootstrap.FormGroup,
    FormControl = ReactBootstrap.FormControl,
    ControlLabel = ReactBootstrap.ControlLabel,
    HelpBlock = ReactBootstrap.HelpBlock;

class InputInfo extends React.Component{
    constructor(props){
        super(props);
        let shower;
        this.state = {
            nameHint: "",
            contactNumberHint: "",
        };

        this.setName = this.setName.bind(this);
        this.setContactNumber = this.setContactNumber.bind(this);
        this.send = this.send.bind(this);
    }
    componentDidMount(){

    }
    setName(){
        const { t } = this.props;
        // clear hint
        if(this.state.nameHint !== "")this.setState({nameHint: ""});

        // set value to global state
        const value = this.nameInput.value;

        // set hint
        this.props.setCheckOrdersInfo("name", value);
        if(value === "") this.setState({nameHint: "nameHint"});
    }
    setContactNumber(){
        const { t } = this.props;
        // clear hint
        if(this.state.contactNumberHint !== "")this.setState({contactNumberHint: ""});

        // set value to global state
        const value = this.numberInput.value;
        this.props.setCheckOrdersInfo("contactNumber", value);
        
        // set hint
        if(value === "") this.setState({contactNumberHint: "contactNumberHint_blank"});
        else if(value.length < 6) this.setState({contactNumberHint: "contactNumberHint_length"});
    }
    send(event){
        event.preventDefault();

        const { t } = this.props;
        let pass = true;
        if(!this.props.checkOrdersInfo.name){
            this.setState({nameHint: "nameHint"});
            this.numberInput.focus();
            pass = false;
        }
        if(!this.props.checkOrdersInfo.contactNumber){
            this.setState({contactNumberHint: "contactNumberHint_blank"});
            this.numberInput.focus();
            pass = false;
        }else if(this.props.checkOrdersInfo.contactNumber.length < 8){
            this.setState({contactNumberHint: "contactNumberHint_length"});
            this.numberInput.focus();
            pass = false;
        }
        if(!pass) return;

        this.props.nextStep();

    }
    render(){
        const { t } = this.props;

        return(
            <Grid>
            <Row className="show-grid">
            <Col md={7}>
                <FormGroup controlId="formControlsSelect">
                    <ControlLabel>{t("reservatorName")}</ControlLabel>
                    <FormControl
                        type="text"
                        placeholder={t("nameHint")+"..."}
                        defaultValue={this.props.checkOrdersInfo.name||""}
                        inputRef={ref => { this.nameInput = ref; }}
                        onChange = {this.setName}
                    />
                    <FormControl.Feedback />
                    <p className="hint">{t(this.state.nameHint)}</p>
                    <ControlLabel>{t("contactNumber")}</ControlLabel>
                    <FormControl
                        type="text"
                        placeholder="0912345678..."
                        defaultValue={this.props.checkOrdersInfo.contactNumber||""}
                        inputRef={ref => { this.numberInput = ref; }}
                        onChange = {this.setContactNumber}
                    />
                    <FormControl.Feedback />
                    <p className="hint">{t(this.state.contactNumberHint)}</p>
                </FormGroup>
             </Col>
             <Button currentStep={0} clickHandle={this.send} disabled={false}/>
            </Row>
        </Grid>
        );
    }
}


const mapStateToProps = (state)=>{
    return {
        checkOrdersInfo: state.checkOrdersInfo
    }
}

const mapDispatchToProps = (dispatch)=>{
    return bindActionCreators({
        setCheckOrdersInfo: setCheckOrdersInfo,
        toggleLoading: toggleLoading
    },dispatch);
}

InputInfo = connect(mapStateToProps,mapDispatchToProps)(InputInfo);  

module.exports = translate()(InputInfo);