// 負責寫資料(師傅,人數,房號,姓名,電話)到global state
import { translate } from 'react-i18next';
import LoadingAnimation from "../LoadingAnimation";
import Button from "./Button";

const Col = ReactBootstrap.Col,
    FormGroup = ReactBootstrap.FormGroup,
    FormControl = ReactBootstrap.FormControl,
    ControlLabel = ReactBootstrap.ControlLabel;

class CheckDetail extends React.Component{
    constructor(props){
        super(props);

        let shower;
        if(this.props.sourceData.services &&  this.props.reservation.service){
            if(this.props.sourceData.services[this.props.reservation.service]) shower = this.props.sourceData.services[this.props.reservation.service].shower > 1;
        }
            
        const reservation = this.props.reservation;
        this.state = {
            nameHint: "",
            contactNumberHint: "",
            operatorHint: "",

            // 程式選中的房間是否會附衛浴，影響房間配置，與客人實際需求無關
            shower: reservation.shower===null? shower : reservation.shower,
            maxGuestNum: -1,

            operator: reservation.operator || [],
            guestNum: reservation.guestNum || -1,
            roomId: reservation.roomId || null,
            name: reservation.name || "",
            contactNumber: reservation.contactNumber || "",

            room: this.props.sourceData.room || null,
            service_provider_list: this.props.sourceData.service_provider_list || null,

            loading: false
        };

        this.setOperator = this.setOperator.bind(this);
        this.setGuestNum = this.setGuestNum.bind(this);
        this.setName = this.setName.bind(this);
        this.setContactNumber = this.setContactNumber.bind(this);
        this.setRoomId = this.setRoomId.bind(this);

        this.setMaxGuestNum = this.setMaxGuestNum.bind(this);
        this.setShower = this.setShower.bind(this);

        this.save = this.save.bind(this);
        this.toggleLoading = this.toggleLoading.bind(this);
    }
    componentDidMount(){
        if(this.state.room){
            // 已輸入過此階段的資料，設定可選擇人數的最大值
            this.setMaxGuestNum();
        }else{
            const that = this,
            csrf_token = document.querySelector('input[name="_token"]').value;    

            this.toggleLoading();
            axios({
                method: "get",
                url: "../api/service_provider_and_room_list",
                params: {
                    service_id: this.props.reservation.service,
                    shop_id: this.props.reservation.shop
                },
                headers: {'X-CSRF-TOKEN': csrf_token},
                responseType: 'json'
            })
            .then(function (response) {
                if(response.statusText == "OK"){
                    that.setState({
                        service_provider_list: response.data.service_provider_list,
                        room: response.data.room,
                        guestNum: 1,
                        operator: [response.data.service_provider_list[0].id]
                    },()=>{
                        that.setMaxGuestNum(that.setRoomId);
                    });
                    that.toggleLoading();
                }
            })
            .catch(function (error) {
                console.log(error);
                that.props.showErrorPopUp();
                that.toggleLoading();
            });
        }
    }
    toggleLoading(){
        this.setState({loading: !this.state.loading});
    }
    setOperator(event){
        const value = +event.target.options[event.target.selectedIndex].value, // id
              index = +event.target.dataset.index; // index to save at

        let operator = JSON.parse(JSON.stringify(this.state.operator));
        operator[index]=value;

        this.setState({
            operator
        });
    }
    setShower(event){
        const el = event.target.options[event.target.selectedIndex],
              value = (el.value=="true");
              
        // get and set max guest num 
        this.setState({shower: value, guestNum: 1},()=>{
            this.setMaxGuestNum(this.setRoomId);
        });
    }
    setGuestNum(event){
        const value = +event.target.options[event.target.selectedIndex].value,
              operator = this.state.service_provider_list.slice(0, value).map(operator=>operator.id);

        this.setState({guestNum: value, operator},()=>{
            this.setRoomId();
        });
    }
    setName(){
        // clear hint
        if(this.state.nameHint !== "")this.setState({nameHint: ""});

        // set value to global state
        const value = this.nameInput.value;

        // set hint
        this.setState({name: value});
        if(value === "") this.setState({nameHint: "nameHint"});
    }
    setContactNumber(){
        // clear hint
        if(this.state.contactNumberHint !== "")this.setState({contactNumberHint: ""});

        // set value to global state
        const value = this.numberInput.value;
        this.setState({contactNumber: value});

        // set hint
        if(value === "") this.setState({contactNumberHint: "contactNumberHint_blank"});
        else if(value.length < 8) this.setState({contactNumberHint: "contactNumberHint_length"});
    }
    //
    setMaxGuestNum(fn){
        // max guest number is set in initializing, and whenever shower option changes, and is decided by service type
        const showerType = this.props.sourceData.services[this.props.reservation.service].shower,
              rooms = this.state.room;

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
        const operatorNum = this.state.service_provider_list.length;
        if(operatorNum < max) max = operatorNum;
        
        this.setState({maxGuestNum: max},()=>{
            if(fn) fn();
        });
    }
    setRoomId(){
        // roomId is set in initializing and whenever shower option or guest number is set
        const rooms = this.state.room,
              guestNum = this.state.guestNum;

        let roomId;
        // 尋找人數剛好符合的房間
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
                    roomId = rooms[i].id;
                    break;
                }
            }
        }
        this.setState({roomId});
    }
    save(event){
        event.preventDefault();

        const { t } = this.props;
        let pass = true;
        if(!this.state.name){
            this.setState({nameHint: "nameHint"});
            this.nameInput.focus();
            pass = false;
        }
        if(!this.state.contactNumber){
            this.setState({contactNumberHint: "contactNumberHint_blank"});
            this.numberInput.focus();
            pass = false;
        }else if(this.state.contactNumber.length < 8){
            this.setState({contactNumberHint: "contactNumberHint_length"});
            this.numberInput.focus();
            pass = false;
        }
        if(!pass) return;

        this.props.saveReservationAndSourceData({
            operator: this.state.operator,
            guestNum: this.state.guestNum,
            roomId: this.state.roomId,
            name: this.state.name,
            contactNumber: this.state.contactNumber,
            shower: this.state.shower
        },{
            room: this.state.room,
            service_provider_list: this.state.service_provider_list
        }, this.props.nextStep);
    }
    render(){
        const { t } = this.props;

        let guestNumEl = [], operators = [];
        if(this.state.maxGuestNum > 0){
            for(let i = 1; i <= this.state.maxGuestNum;i++){
                // options of guest number
                guestNumEl.push(<option key={i} value={i}>{i}</option>);
            }
        }

        const selectedOperators = this.state.operator;
        if(this.state.guestNum > 0 && this.state.service_provider_list){
            for(let i = 0; i < this.state.guestNum;i++){
                // options of operators
                operators.push(<FormControl bsClass="form-control operatorOption" componentClass="select" id={"operator"+i} data-index={i} onChange={this.setOperator} defaultValue={this.state.operator[i]?this.state.operator[i]:null} value={this.state.operator[i]?this.state.operator[i]:null}>
                    {this.state.service_provider_list.map((operator, index)=>{
                        let selected = false;
                        for(let j = 0 ; j < selectedOperators.length ; j++){
                            if(j === i) continue; // 當前的跟不指定不用確認
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
            <div>
            <FormGroup>
                <Col md={5}>
                    {this.state.roomId && <div style={{marginBottom: "5px"}}><ControlLabel>{t("guestNum")}</ControlLabel>
                    <FormControl componentClass="select" placeholder="select" defaultValue={this.state.guestNum} onChange={this.setGuestNum} value={this.state.guestNum}>
                        {guestNumEl}
                    </FormControl></div>}
                    { (this.props.sourceData.services &&  this.props.reservation.service) && this.props.sourceData.services[this.props.reservation.service].shower === 1 && 
                        <div style={{marginBottom: "5px"}}>
                            <ControlLabel>{t("showerOrNot")}</ControlLabel>
                            <FormControl componentClass="select" placeholder="select" defaultValue={this.state.shower} onChange={this.setShower}
                             inputRef={ref => { this.showerOrNot = ref; }}>
                                <option value={true}>{t("yes")}</option>
                                <option value={false}>{t("no")}</option>
                            </FormControl>
                        </div>
                    }
                    {this.state.roomId?<div>
                        <ControlLabel>{t("operator")}</ControlLabel>
                        {operators}
                    </div>:<p className="hint">{t("errorHint_noRoom")}</p>}
               </Col>
               
               <Col md={1}>
               <div className="divider"></div>
               </Col>

               <Col md={5}>
                    <ControlLabel>{t("reservatorName")}</ControlLabel>
                    <FormControl
                        type="text"
                        placeholder={t("nameHint")+"..."}
                        inputRef={ref => { this.nameInput = ref; }}
                        onChange = {this.setName}
                        defaultValue={this.state.name}
                    />
                    <FormControl.Feedback />
                    <p className="hint">{t(this.state.nameHint)}</p>
                    <ControlLabel>{t("contactNumber")}</ControlLabel>
                    <FormControl
                        type="text"
                        placeholder="0912345678..."
                        inputRef={ref => { this.numberInput = ref; }}
                        onChange = {this.setContactNumber}
                        defaultValue={this.state.contactNumber}
                    />
                    <FormControl.Feedback />
                    <p className="hint">{t(this.state.contactNumberHint)}</p>
                </Col>
             </FormGroup>
             <Button currentStep={1} clickHandle={this.save} disabled={this.state.loading}/>
             {this.state.loading && <Col md={12}><LoadingAnimation /></Col>}
            </div>
        );
    }
}

module.exports = translate()(CheckDetail);