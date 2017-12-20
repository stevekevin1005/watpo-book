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
            maxGuestNum: -1,
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
    componentDidMount(){
        const data = this.props.sourceData.timeList[this.props.sourceData.selectedDetail].detail;
        this.props.setReservation("operator", data.service_provider_list[0].id);
        this.props.setReservation("room", flatten(data.room)[0].id);
        this.props.setReservation("guestNum", "1");
        this.setState({maxGuestNum: flatten(data.room)[0].person});
    }
    setOperator(event){
        const value = event.target.options[event.target.selectedIndex].value;
        this.props.setReservation("operator", value);
    }
    setRoom(event){
        const el = event.target.options[event.target.selectedIndex],
              value = el.value,
              maxNum = el.getAttribute("data-maxNum");
        this.setState({maxGuestNum: maxNum},()=>{
            this.props.setReservation("room", value);
        });
    }
    setGuestNum(event){
        const value = event.target.options[event.target.selectedIndex].value;
        this.props.setReservation("guestNum", value);
    }
    setName(){
        this.props.setReservation("name", this.nameInput.value);
    }
    setContactNumber(){
        this.props.setReservation("contactNumber", this.numberInput.value);
    }
    send(){

    }
    render(){
        if(this.props.sourceData.timeList === undefined || this.props.sourceData.selectedDetail === undefined) location.href = '../reservation/0';

        const { t } = this.props,
              data = this.props.sourceData.timeList[this.props.sourceData.selectedDetail].detail;
        
        let guestNumEl = [];

        if(this.state.maxGuestNum >= 0){
            for(let i = 1; i <= this.state.maxGuestNum;i++){
                guestNumEl.push(<option key={i} value={i}>{i}</option>);
            }
        }
        return(
            <Grid>
            <Row className="show-grid">
            <FormGroup controlId="formControlsSelect">
                <Col md={5}>
                        <ControlLabel>{t("operator")}</ControlLabel>
                        <FormControl componentClass="select" id="operator" placeholder="select" onChange={this.setOperator}>
                            {data.service_provider_list.map((operator, index)=>{
                                return (<option key={index} value={operator.id}>{operator.name}</option>);
                            })}
                        </FormControl>
                        <FormControl.Feedback />
                        <HelpBlock></HelpBlock>
                        <ControlLabel>{t("roomNumber")}</ControlLabel>
                        <FormControl componentClass="select" placeholder="select" onChange={this.setRoom}>
                            {flatten(data.room).map((room, index)=>{
                                return (<option key={index} value={room.id} data-maxNum={room.person}>{room.name + " ( "+room.person + "人房, "+ (room.shower?"附":"無") + "衛浴)"}</option>);
                            })}
                        </FormControl>
                        <FormControl.Feedback />
                        <HelpBlock></HelpBlock>
                        <ControlLabel>{t("guestNum")}</ControlLabel>
                        <FormControl componentClass="select" placeholder="select" onChange={this.setGuestNum}>
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
                        placeholder="請輸入預約人姓名..."
                        inputRef={ref => { this.nameInput = ref; }}
                        onChange = {this.setName}
                    />
                    <FormControl.Feedback />
                    <HelpBlock>{this.state.nameHint}</HelpBlock>
                    <ControlLabel>{t("contactNumber")}</ControlLabel>
                    <FormControl
                        type="text"
                        placeholder="輸入連絡電話..."
                        inputRef={ref => { this.numberInput = ref; }}
                        onChange = {this.setContactNumber}
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


function flatten(obj){
    let rooms = Object.values(obj), flattened = [];
    rooms.forEach((room)=>{
        flattened = flattened.concat(Object.values(room));
    });
    flattened = flattened.reduce((arr, nextArr)=>{
        return arr.concat(nextArr);
    });
    
    return flattened;
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