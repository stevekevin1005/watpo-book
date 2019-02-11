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
            longTimePeriodChoose: false
        };

        this.getTimePeriods = this.getTimePeriods.bind(this);
        this.setTime = this.setTime.bind(this);
        this.setDate = this.setDate.bind(this);
        this.clearDateAndTimeAndTimeList = this.clearDateAndTimeAndTimeList.bind(this);
        this.setLongTimePeriod = this.setLongTimePeriod.bind(this);
        if(this.props.reservation.shop == 1) {
            this.earlyMorning = "00:00 - 04:00";
            this.noon = "12:00 - 15:30";
            this.afternoon = "16:00 - 19:30";
            this.night = "20:00 - 23:30";
        }
        else {
            this.earlyMorning = "23:00 - 03:00";
            this.noon = "11:00 - 14:30";
            this.afternoon = "15:00 - 18:30";
            this.night = "19:00 - 22:30";
        }

        let today = new Date();
        let day = today.getDate();
        let month = today.getMonth()+1;
        let year = today.getFullYear();
        this.date = year+"/"+ (month<10?"0"+month:month) +"/"+ (day<10?"0"+day:day);
    }    
    componentDidMount(){
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1;
        var yyyy = today.getFullYear();
        this.clearLongTimePeriodChoose();
    }
    getTimePeriods(startTime, endTime){
        const that = this, 
            reservation = this.props.reservation,
            date = this.date,
            csrf_token = document.querySelector('input[name="_token"]').value;

        this.props.toggleLoading();
        axios({
            method: "get",
            url: "../api/time_list",
            params: {
                shop_id: reservation.shop, 
                service_id: reservation.service,
                date: date,
                person: reservation.guestNum,
                service_provider_id: reservation.operator.join(),
                room_id: reservation.roomId,
                shower: reservation.shower,
                start_time: startTime,
                end_time: endTime
            },
            headers: {'X-CSRF-TOKEN': csrf_token},
            responseType: 'json'
        })
        .then(function (response) {
            if(response.statusText == "OK"){
                that.props.setSourceData({timeList: response.data});
                if(that.props.loading) that.props.toggleLoading();
                if(response.data.length === 0) that.setState({hint: "calendarError_noTimelist"});
                that.setState(function(state) {
                    return {longTimePeriodChoose: true};
                });
            }
        })
        .catch(function (error) {
            console.log(error);
            that.props.toggleLoading();
            that.setState({hint: "errorHint_system"});
        });
    }
    setTime(event){
        const time = event.target.innerHTML;
        this.props.setReservation({time});
    }
    setDate(year,month,day) {
        let date = year+"/"+ (month<10?"0"+month:month) +"/"+ (day<10?"0"+day:day);
        this.date = date;
        this.clearLongTimePeriodChoose();
        this.props.setReservation({date});
    }
    setLongTimePeriod(option) {
        var timeInterval =  option.split(" - ");
        this.getTimePeriods(timeInterval[0], timeInterval[1]);
    }
    clearLongTimePeriodChoose() {
        this.setState(function(state) {
            return {longTimePeriodChoose: false};
        });
    }
    clearDateAndTimeAndTimeList(){
        this.clearLongTimePeriodChoose();
        this.props.setReservation({
            date: null,
            time: null
        },()=>{
            this.props.setSourceData({timeList: null});
        });
    }

    render(){
        if(!this.props.reservation.roomId) location.href = '../reservation/0';
        const reservation = this.props.reservation,
              disabled = (!reservation.date || !reservation.time) || this.props.loading,
              { t } = this.props;
        return(
            <div>
                <Col md={5}>
                    <Calendar 
                        selectDayHandle={this.setDate} 
                        date={reservation.date}
                        changeMonthHandle={this.clearDateAndTimeAndTimeList}
                    />
                </Col>
                <Col md={5}>
                    
                    {!this.state.longTimePeriodChoose ? (
                        <div className="timePeriods">
                            <span className="timePeriod available" onClick={() => this.setLongTimePeriod(this.earlyMorning)}>{this.earlyMorning}</span>
                            <span className="timePeriod available" onClick={() => this.setLongTimePeriod(this.noon)}>{this.noon}</span>
                            <span className="timePeriod available" onClick={() => this.setLongTimePeriod(this.afternoon)}>{this.afternoon}</span>
                            <span className="timePeriod available" onClick={() => this.setLongTimePeriod(this.night)}>{this.night}</span>
                            <p className="hint">{t("timeHint")}</p>
                        </div>
                    ) : (
                        <div className="timePeriods">
                             {this.props.sourceData.timeList?this.props.sourceData.timeList.map((time,index)=>{
                                if(time.time == this.props.reservation.time) return (<span className="timePeriod selectedTime" key={index} data-index={index}>{time.time}</span>);
                                return (<span className={time.select?"timePeriod available":"timePeriod"} key={index} data-index={index} onClick={time.select?this.setTime:null}>{time.time}</span>);
                            }):<p>{t(this.state.hint)}</p>}
                            <p className="hint">{t("timeHint")}</p>
                        </div>
                    )}
                </Col>
                <Button currentStep={2} clickHandle={this.props.send} disabled={disabled}/>
            </div>
        );
    }
}

module.exports = translate()(CheckTime);