// 負責寫資料(日期,時段)到global state
import { translate } from 'react-i18next';
import Calendar from "./Calendar";
import LoadingAnimation from "../LoadingAnimation";
import Button from "./Button";

const Col = ReactBootstrap.Col,
    ListGroupItem = ReactBootstrap.ListGroupItem,
    ListGroup = ReactBootstrap.ListGroup;

class CheckTime extends React.Component{
    constructor(props){
        super(props);

        this.state = {
            hint: "calendarHint",
            date: null,
            time: null,

            timeList: null
        };

        this.getTimePeriods = this.getTimePeriods.bind(this);
        this.setTime = this.setTime.bind(this);
        this.clearDateAndTimeAndTimeList = this.clearDateAndTimeAndTimeList.bind(this);
        this.saveAndSend = this.saveAndSend.bind(this);
        this.toggleLoading = this.toggleLoading.bind(this);
    }    
    toggleLoading(){
        this.setState({loading: !this.state.loading});
    }
    getTimePeriods(year,month,day){
        const that = this, 
        date = year+"/"+ (month<10?"0"+month:month) +"/"+ (day<10?"0"+day:day),
        csrf_token = document.querySelector('input[name="_token"]').value;
        
        this.setState({date});

        this.toggleLoading();
        axios({
            method: "get",
            url: "../api/time_list",
            params: {
                shop_id: this.props.reservation.shop, 
                service_id: this.props.reservation.service,
                date: date,
                person: this.props.reservation.guestNum,
                service_provider_id: this.props.reservation.operator.join(""),
                room_id: this.props.reservation.roomId
            },
            headers: {'X-CSRF-TOKEN': csrf_token},
            responseType: 'json'
        })
        .then(function (response) {
            if(response.statusText == "OK"){
                that.setState({timeList: response.data});

                that.toggleLoading();
                if(response.data.length === 0) that.setState({hint: "calendarError_noTimelist"});
            }
        })
        .catch(function (error) {
            console.log(error);
            that.toggleLoading();
            that.setState({hint: "errorHint_system"});
        });
    }
    setTime(event){
        const time = event.target.innerHTML;
        this.setState({time});
    }
    clearDateAndTimeAndTimeList(){
        this.setState({
            date: null,
            time: null,
            timeList: null
        });
    }
    saveAndSend(){
        this.props.saveReservationAndSourceData({
            time: this.state.time,
            date: this.state.date
        }, {
            timeList: this.state.timeList
        }, this.props.send);
    }
    render(){
        if(!this.props.reservation.roomId) location.href = '../reservation/0';
        const reservation = this.props.reservation,
              disabled = (!this.state.date || !this.state.time) || this.props.loading,
              { t } = this.props;

        return(
            <div>
                <Col md={5}>
                    <Calendar 
                        getTimePeriods={this.getTimePeriods} 
                        date={this.state.date}
                        clearDateAndTimeAndTimeList={this.clearDateAndTimeAndTimeList}
                    />
                </Col>
                <Col md={5}>
                    <div className="timePeriods">
                        {this.state.timeList?this.state.timeList.map((time,index)=>{
                            if(time.time == this.state.time) return (<span className="timePeriod selectedTime" key={index} data-index={index}>{time.time}</span>);
                            return (<span className={time.select?"timePeriod available":"timePeriod"} key={index} data-index={index} onClick={time.select?this.setTime:null}>{time.time}</span>);
                        }):<p>{t(this.state.hint)}</p>}
                    </div>
                    <p className="hint">{t("timeHint")}</p>
                </Col>
                <Button currentStep={2} clickHandle={this.saveAndSend} disabled={disabled}/>
                {this.state.loading && <Col md={12}><LoadingAnimation /></Col>}
            </div>
        );
    }
}

module.exports = translate()(CheckTime);