// 負責寫資料(日期,時段)到global state
import { translate } from 'react-i18next';
import Calendar from "./Calendar";
import Button from "./Button";

const Grid = ReactBootstrap.Grid,
    Row = ReactBootstrap.Row,
    Col = ReactBootstrap.Col,
    ListGroupItem = ReactBootstrap.ListGroupItem,
    ListGroup = ReactBootstrap.ListGroup;

class CheckTime extends React.Component{
    constructor(props){
        super(props);

        this.state = {hint: "calendarHint"};

        this.getTimePeriods = this.getTimePeriods.bind(this);
        this.setTime = this.setTime.bind(this);
    }    
    getTimePeriods(year,month,day){
        const that = this, 
        date = year+"/"+ (month<10?"0"+month:month) +"/"+ (day<10?"0"+day:day),
        csrf_token = document.querySelector('input[name="_token"]').value;

        // clear selected detail index
        this.props.clearSourceData("selectedDetail");

        this.props.toggleLoading(true);

        this.props.setReservation("date", date);

        axios({
            method: "get",
            url: "../api/time_list",
            params: {
                shop_id: this.props.sourceData.shops[this.props.reservation.shop].id, 
                service_id: this.props.sourceData.services[this.props.reservation.service].id,
                date: date
            },
            headers: {'X-CSRF-TOKEN': csrf_token},
            responseType: 'json'
        })
        .then(function (response) {
            console.log("response", response);
            if(response.statusText == "OK"){

                that.props.setSourceData("timeList", response.data);

                that.props.toggleLoading(false);
                if(response.data.length === 0) that.setState({hint: "calendarError_noTimelist"});
            }
        })
        .catch(function (error) {
            console.log(error);
            that.props.toggleLoading(false);
            that.setState({hint: "errorHint_system"});
        });
    }
    setTime(event){
        const value = event.target.innerHTML,
              index = +event.target.dataset.index;
        this.props.setReservation("time", value);
        this.props.setSourceData("selectedDetail", index);
    }
    render(){
        console.log(this.props.reservation);
        if(!(this.props.reservation.shop || this.props.reservation.shop === 0) || !( this.props.reservation.service || this.props.reservation.service === 0)) location.href = '../reservation/0';
        const reservation = this.props.reservation,
              disabled = (!reservation.date || !reservation.time),
              { t } = this.props;;

        return(
            <Grid>
            <Row className="show-grid">
            <Col md={5}>
                <Calendar getTimePeriods={this.getTimePeriods}/>
            </Col>
            <Col md={5}>
                <div className="timePeriods">
                    {this.props.sourceData.timeList?this.props.sourceData.timeList.map((time,index)=>{
                        if(time.detail.service_provider_list === null || time.detail.room === null) return (<span className="timePeriod" key={index} data-index={index}>{time.time}</span>);
                        else if(index === this.props.sourceData.selectedDetail) return (<span className="timePeriod selectedTime" key={index} data-index={index}>{time.time}</span>);
                        return (<span className="timePeriod available" key={index} data-index={index} onClick={this.setTime}>{time.time}</span>);
                    }):<p>{t(this.state.hint)}</p>}
                </div>
                <p className="hint">{t("timeHint")}</p>
            </Col>
            <Button currentStep={1} clickHandle={this.props.nextStep} disabled={disabled}/>
            </Row>
            </Grid>
        );
    }
}

module.exports = translate()(CheckTime);