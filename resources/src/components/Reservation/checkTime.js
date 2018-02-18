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
            hint: "calendarHint"
        };

        this.getTimePeriods = this.getTimePeriods.bind(this);
        this.setTime = this.setTime.bind(this);
        this.clearDateAndTimeAndTimeList = this.clearDateAndTimeAndTimeList.bind(this);
    }    
    getTimePeriods(year,month,day){
        const that = this, 
            reservation = this.props.reservation,
            date = year+"/"+ (month<10?"0"+month:month) +"/"+ (day<10?"0"+day:day),
            csrf_token = document.querySelector('input[name="_token"]').value;
        
        this.props.setReservation({date});

        this.props.toggleLoading();
        axios({
            method: "get",
            url: "../api/time_list",
            params: {
                shop_id: reservation.shop, 
                service_id: reservation.service,
                date: date,
                person: reservation.guestNum,
                service_provider_id: reservation.operator.join(""),
                room_id: reservation.roomId
            },
            headers: {'X-CSRF-TOKEN': csrf_token},
            responseType: 'json'
        })
        .then(function (response) {
            if(response.statusText == "OK"){
                that.props.setSourceData({timeList: response.data});

                that.props.toggleLoading();
                if(response.data.length === 0) that.setState({hint: "calendarError_noTimelist"});
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
    clearDateAndTimeAndTimeList(){
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
                        selectDayHandle={this.getTimePeriods} 
                        date={this.props.reservation.date}
                        changeMonthHandle={this.clearDateAndTimeAndTimeList}
                    />
                </Col>
                <Col md={5}>
                    <div className="timePeriods">
                        {this.props.sourceData.timeList?this.props.sourceData.timeList.map((time,index)=>{
                            if(time.time == this.props.reservation.time) return (<span className="timePeriod selectedTime" key={index} data-index={index}>{time.time}</span>);
                            return (<span className={time.select?"timePeriod available":"timePeriod"} key={index} data-index={index} onClick={time.select?this.setTime:null}>{time.time}</span>);
                        }):<p>{t(this.state.hint)}</p>}
                    </div>
                    <p className="hint">{t("timeHint")}</p>
                </Col>
                <Button currentStep={2} clickHandle={this.props.send} disabled={disabled}/>
            </div>
        );
    }
}

module.exports = translate()(CheckTime);