// 負責寫資料(師傅,人數,房號,姓名,電話)到global state
import { translate } from 'react-i18next';
import {connect} from "react-redux";
import {bindActionCreators} from "redux";
import setReservation from "../../dispatchers/setReservation";
import clearReservation from "../../dispatchers/clearReservation";
import Button from "./Button";

const Grid = ReactBootstrap.Grid,
    Row = ReactBootstrap.Row,
    Col = ReactBootstrap.Col,
    FormGroup = ReactBootstrap.FormGroup,
    FormControl = ReactBootstrap.FormControl,
    ControlLabel = ReactBootstrap.ControlLabel,
    HelpBlock = ReactBootstrap.HelpBlock;

class CheckDetail extends React.Component{
    constructor(props){
        super(props);
        let shower;
        if(this.props.sourceData.services !== undefined &&  this.props.reservation.service !== undefined) shower = this.props.sourceData.services[this.props.reservation.service].shower > 1;
        this.state = {
            maxGuestNum: -1,
            prevGuestNum: 1,
            guestNum: 1,
            // 程式選中的房間是否會附衛浴，影響房間配置，與客人實際需求無關
            shower: shower,
            nameHint: "",
            contactNumberHint: "",
            operatorHint: ""
        };

        this.setOperator = this.setOperator.bind(this);
        this.setShower = this.setShower.bind(this);
        this.setGuestNum = this.setGuestNum.bind(this);
        this.setName = this.setName.bind(this);
        this.setContactNumber = this.setContactNumber.bind(this);
        this.setMaxGuestNum = this.setMaxGuestNum.bind(this);
        this.setRoomId = this.setRoomId.bind(this);
        this.send = this.send.bind(this);
    }
    componentDidMount(){
        // initializing
        const data = this.props.sourceData.timeList[this.props.sourceData.selectedDetail].detail;
            this.props.setReservation("operator", {index:0, data:data.service_provider_list[0].id});
            this.props.setReservation("guestNum", 1);

            this.setState({guestNum: 1},()=>{
                // set default max guest number and room id
                this.setMaxGuestNum(this.setRoomId);
            });
    }
    //
    setOperator(event){
        const value = parseInt(event.target.options[event.target.selectedIndex].value), // id
              index = parseInt(event.target.getAttribute("data-index")); // index to save at
        this.props.setReservation("operator", {
            index: index,
            data: value
        });
    }
    setShower(event){
        const el = event.target.options[event.target.selectedIndex],
              value = el.value=="true";
        // get and set max guest num 
        this.setState({shower: value, guestNum: 1},()=>{
            this.props.setReservation("guestNum", 1);
            this.setMaxGuestNum(this.setRoomId);
        });
    }
    setGuestNum(event){
        const value = parseInt(event.target.options[event.target.selectedIndex].value),
              data = this.props.sourceData.timeList[this.props.sourceData.selectedDetail].detail;

        // set guest number and set room id
        this.props.setReservation("guestNum", value);
        this.setState({guestNum: value, prevGuestNum: this.state.guestNum},()=>{
        this.setRoomId();

            // set default operator data
            if(value > this.state.prevGuestNum){
                // 把師父資料全部重設
                for(let i=0;i<value;i++){
                    this.props.setReservation("operator", {
                        index: i,
                        data: data.service_provider_list[i].id
                    });
                }
            }else{
                this.props.clearReservation("operator", null, value);
            }

        });
    }
    setName(){
        // clear hint
        if(this.state.nameHint !== "")this.setState({nameHint: ""});

        // set value to global state
        const value = this.nameInput.value;

        // set hint
        this.props.setReservation("name", value);
        if(value === "") this.setState({nameHint: "請輸入聯絡人姓名"});
    }
    setContactNumber(){
        // clear hint
        if(this.state.contactNumberHint !== "")this.setState({contactNumberHint: ""});

        // set value to global state
        const value = this.numberInput.value;
        this.props.setReservation("contactNumber", value);
        
        // set hint
        if(value === "") this.setState({contactNumberHint: "請輸入聯絡號碼"});
        else if(value.length < 6) this.setState({contactNumberHint: "請輸入有效聯絡號碼"});
    }
    //
    setMaxGuestNum(fn){
        // max guest number is set in initializing, and whenever shower option changes, and is decided by service type
        const showerType = this.props.sourceData.services[this.props.reservation.service].shower,
              data = this.props.sourceData.timeList[this.props.sourceData.selectedDetail].detail,
              rooms = data.room;
        let max = 0;
        switch(showerType){
            case 0:
                for(let i = 0; i < rooms.length; i++){
                    // 搜尋無衛浴的房間
                    if(rooms[i].shower === 0 && rooms[i].person > max) max = rooms[i].person;
                }
                if(max === 0){
                    this.setState({shower: true});
                    for(let i = 0; i < rooms.length; i++){
                        // 搜尋附衛浴的房間
                        if(rooms[i].shower === 1 && rooms[i].person > max) max = rooms[i].person;
                    }
                }
                break;
            case 1:
                // 先搜尋符合客人需求的房間
                for(let i = 0; i < rooms.length; i++){
                    // 1 == true, 0 == false ...
                    if(rooms[i].shower == this.state.shower && rooms[i].person > max) max = rooms[i].person;
                }
                // 客人想選無衛浴的房間但無符合項目，於是配有衛浴的房間
                if(max === 0 && this.state.shower === false){
                    for(let i = 0; i < rooms.length; i++){
                        // 1 == true, 0 == false ...
                        if(rooms[i].shower == true && rooms[i].person > max) max = rooms[i].person;   
                    }
                    this.setState({shower: true});
                // 客人想選有衛浴的房間但無符合項目
                }else if(max === 0 && this.state.shower === true){
                    this.setState({guestNum: -1});
                    this.props.setReservation("guestNum", undefined);
                    fn(true);
                    return;
                }
                break;
            case 2:
                // API傳回的房間皆附衛浴
                for(let i = 0; i < rooms.length; i++){
                    if(rooms[i].shower === 1 && rooms[i].person > max) max = rooms[i].person;
                }
        }
        // 確認目前可提供服務的師傅數量
        const operatorsNum = data.service_provider_list.length;
        if(operatorsNum < max) max = operatorsNum;
        this.setState({maxGuestNum: max},fn());
    }
    setRoomId(noRoom){
        if(noRoom){
            this.props.setReservation("room", undefined);
            this.props.clearReservation("operator", null, 1);
            return;
        }
        // roomId is set in initializing and whenever shower option or guest number is set
        const rooms = this.props.sourceData.timeList[this.props.sourceData.selectedDetail].detail.room,
              guestNum = this.state.guestNum;

        let roomId;
        for(let i = 0; i < rooms.length; i++){
            if(rooms[i].shower == this.state.shower && rooms[i].person == guestNum){
                // console.log(rooms[i]);
                roomId = rooms[i].id;
                break;
            }
        }
        // 假如沒有人數剛好符合的房間，找人數較多的
        if(roomId === undefined){
            for(let i = 0; i < rooms.length; i++){
                if(rooms[i].shower == this.state.shower && rooms[i].person > guestNum){
                    // console.log(rooms[i]);
                    roomId = rooms[i].id;
                    break;
                }
            }
        }
        this.props.setReservation("room", roomId);
    }
    send(event){
        event.preventDefault();

        let pass = true;
        if(!this.props.reservation.name){
            this.setState({nameHint: "請輸入聯絡人姓名"});
            this.numberInput.focus();
            pass = false;
        }
        if(!this.props.reservation.contactNumber){
            this.setState({contactNumberHint: "請輸入聯絡號碼"});
            this.numberInput.focus();
            pass = false;
        }else if(this.props.reservation.contactNumber.length < 6){
            this.setState({contactNumberHint: "請輸入有效聯絡號碼"});
            this.numberInput.focus();
            pass = false;
        }
        if(!pass) return;

        this.props.send();
    }
    render(){
        // redirect if reservation of time or date is not set
        if(this.props.sourceData.timeList === undefined || this.props.sourceData.selectedDetail === undefined) location.href = '../reservation/0';

        const { t } = this.props,
              data = this.props.sourceData.timeList[this.props.sourceData.selectedDetail].detail;
        
        let guestNumEl = [], operators = [];
        if(this.state.maxGuestNum > 0){
            for(let i = 1; i <= this.state.maxGuestNum;i++){
                // options of guest number
                guestNumEl.push(<option key={i} value={i}>{i}</option>);
            }
        }

        const selectedOperators = this.props.reservation.operator;
        if(this.state.guestNum>0){
            for(let i = 0; i < this.state.guestNum;i++){
                // options of operators
                operators.push(<FormControl bsClass="form-control operatorOption" componentClass="select" id={"operator"+i} data-index={i} onChange={this.setOperator} defaultValue={this.props.reservation.operator[i]}>
                    {data.service_provider_list.map((operator, index)=>{
                        let selected = false;
                        for(let j = 0 ; j < selectedOperators.length ; j++){
                            if(j === i) continue; // 當前的不用確認
                            if(operator.id == selectedOperators[j]){
                              selected = true;
                              break;
                           }
                         }
                         
                        if(selected) return null;
                        return (<option key={index} value={operator.id}>{operator.name}</option>);
                    })}
                </FormControl>);
            }
        }


        return(
            <Grid>
            <Row className="show-grid">
            <FormGroup controlId="formControlsSelect">
                <Col md={5}>
                        <ControlLabel>{t("operator")}</ControlLabel>
                            {operators}
                        <FormControl.Feedback />
                    { this.props.sourceData.services[this.props.reservation.service].shower === 1 && 
                        <div>
                            <ControlLabel>{"是否需要衛浴?"}</ControlLabel>
                            <FormControl componentClass="select" placeholder="select" defaultValue={this.state.shower} onChange={this.setShower}
                             inputRef={ref => { this.showerOrNot = ref; }}>
                                <option value={true}>{"是"}</option>
                                <option value={false}>{"否"}</option>
                            </FormControl>
                            <FormControl.Feedback />
                        </div>
                    }
                        {this.props.reservation.room?<div><ControlLabel>{t("guestNum")}</ControlLabel>
                        <FormControl componentClass="select" placeholder="select" value={this.state.guestNum} onChange={this.setGuestNum}>
                            {guestNumEl}
                        </FormControl>
                        <FormControl.Feedback />
                        </div>:<p className="hint">目前無符合您需求的房間</p>}
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
                    <p className="hint">{this.state.nameHint}</p>
                    <ControlLabel>{t("contactNumber")}</ControlLabel>
                    <FormControl
                        type="text"
                        placeholder="0912345678..."
                        inputRef={ref => { this.numberInput = ref; }}
                        onChange = {this.setContactNumber}
                    />
                    <FormControl.Feedback />
                    <p className="hint">{this.state.contactNumberHint}</p>
                </Col>
             </FormGroup>
             <Button currentStep={2} clickHandle={this.send} disabled={false}/>
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
        setReservation: setReservation,
        clearReservation: clearReservation
    },dispatch);
}

CheckDetail = connect(mapStateToProps,mapDispatchToProps)(CheckDetail);  

module.exports = translate()(CheckDetail);