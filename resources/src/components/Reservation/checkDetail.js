// 負責寫資料(師傅,人數,房號,姓名,電話)到global state
import { translate } from 'react-i18next';
import {connect} from "react-redux";
import {bindActionCreators} from "redux";
import setReservation from "../../dispatchers/setReservation";

const Button = ReactBootstrap.Button,
    Grid = ReactBootstrap.Grid,
    Row = ReactBootstrap.Row,
    Col = ReactBootstrap.Col,
    FormGroup = ReactBootstrap.FormGroup,
    FormControl = ReactBootstrap.FormControl,
    ControlLabel = ReactBootstrap.ControlLabel,
    HelpBlock = ReactBootstrap.HelpBlock;

class CheckDetail extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            selectedRoom: -1,
            nameHint: "",
            contactNumberHint: ""
        };

        this.setOperator = this.setOperator.bind(this);
        this.setRoom = this.setRoom.bind(this);
        this.setGuestNum = this.setGuestNum.bind(this);
        this.setName = this.setName.bind(this);
        this.setContactNumber = this.setContactNumber.bind(this);
        this.send = this.send.bind(this);
    }
    setOperator(event){
        this.props.setReservation("operator", event.target.value);
    }
    setRoom(event){
        const value = event.target.value;
        this.setState({selectedRoom: parseInt(value)},()=>{
            this.props.setReservation("room", value);
        });
    }
    setGuestNum(event){
        this.props.setReservation("guestNum", event.target.value);
    }
    setName(value){
        this.props.setReservation("name", value);
    }
    setContactNumber(value){
        this.props.setReservation("contactNumber", value);
    }
    send(){

    }
    render(){
        const { t } = this.props,
              data = this.props.sourceData.timelist[this.props.sourceData.selectedDetail].detail;
              
        let guestNumEl = [];

        if(this.state.selectedRoom >= 0){
            for(let i = 1; i <= parseInt(data.room[this.state.selectedRoom].person);i++){
                guestNumEl.push(<option key={i} value={i}>{i}</option>);
            }
        }

        return(
            <Grid>
            <Row className="show-grid">
            <FormGroup controlId="formControlsSelect">
                <Col md={5}>
                        <ControlLabel>{t("operator")}</ControlLabel>
                        <FormControl componentClass="select" id="operator" placeholder="select">
                            {data.service_provider_list.map((operator, index)=>{
                                return (<option key={index} value={operator.id}>{operator.name}</option>);
                            })}
                        </FormControl>
                        <FormControl.Feedback />
                        <HelpBlock></HelpBlock>
                        <ControlLabel>{t("roomNumber")}</ControlLabel>
                        <FormControl componentClass="select" placeholder="select">
                            {data.room.map((room, index)=>{
                                return (<option key={index} value={room.id}>{room.name}</option>);
                            })}
                        </FormControl>
                        <FormControl.Feedback />
                        <HelpBlock></HelpBlock>
                        <ControlLabel>{t("guestNum")}</ControlLabel>
                        <FormControl componentClass="select" placeholder="select">
                            {guestNumEl}
                        </FormControl>
                        <FormControl.Feedback />
                        <HelpBlock></HelpBlock>
               </Col>
               
               <Col md={1}>
               <div className="divider"></div>
               </Col>

               <Col md={5}>
                    <ControlLabel>{t("reservatorName")}</ControlLabel>
                    <FormControl
                        type="text"
                        placeholder="Enter text"
                    />
                    <FormControl.Feedback />
                    <HelpBlock>{this.state.nameHint}</HelpBlock>
                    <ControlLabel>{t("contactNumber")}</ControlLabel>
                    <FormControl
                        type="text"
                        placeholder="Enter text"
                    />
                    <FormControl.Feedback />
                    <HelpBlock>{this.state.contactNumberHint}</HelpBlock>
                </Col>
             </FormGroup>
            </Row>
        </Grid>
        );
    }
}

const mapStateToProps = (state)=>{
    return {
        reservation: state.reservation,
        sourceData: state.sourceData
    }
}

const mapDispatchToProps = (dispatch)=>{
    return bindActionCreators({
        setReservation: setReservation
    },dispatch);
}

CheckDetail = connect(mapStateToProps,mapDispatchToProps)(CheckDetail);  

module.exports = translate()(CheckDetail);