// 負責寫資料(日期,時段)到global state

import Calendar from "./Calendar";
import {connect} from "react-redux";
import {bindActionCreators} from "redux";
import toggleLoading from "../../dispatchers/toggleLoading";
import setReservation from "../../dispatchers/setReservation";
import setSourceData from "../../dispatchers/setSourceData";
import clearSourceData from "../../dispatchers/clearSourceData";
import Button from "./Button";

const Grid = ReactBootstrap.Grid,
    Row = ReactBootstrap.Row,
    Col = ReactBootstrap.Col,
    ListGroupItem = ReactBootstrap.ListGroupItem,
    ListGroup = ReactBootstrap.ListGroup;

class CheckTime extends React.Component{
    constructor(props){
        super(props);

        this.state = {hint: "請點擊月曆選擇日期"};

        this.getTimePeriods = this.getTimePeriods.bind(this);
        this.setTime = this.setTime.bind(this);
    }
    getTimePeriods(year,month,day){
        const that = this, date = year+"/"+ (month<10?"0"+month:month) +"/"+ (day<10?"0"+day:day),
              csrf_token = document.querySelector('input[name="_token"]').value;

        // clear selected detail index
        this.props.clearSourceData("selectedDetail");

        // set loading state
        that.setState({hint: ""});
        this.props.toggleLoading(true);

        this.props.setReservation("date", date);
        this.props.setSourceData("timelist",
            {
                shop: this.props.sourceData.shops[this.props.reservation.shop].id, 
                service: this.props.sourceData.services[this.props.reservation.service].id,
                date: date,
                token: csrf_token
            },
            (length)=>{
                that.props.toggleLoading(false);
                if(length === 0) that.setState({hint: "目前無符合時段"});
            },()=>{
                that.props.toggleLoading(false);
                that.setState({hint: "某處發生錯誤，請重新嘗試"});
            });
    }
    setTime(event){
        const value = event.target.innerHTML,
              index = event.target.getAttribute("data-index");
        this.props.setReservation("time", value);
        this.props.setSourceData("selectedDetail", parseInt(index));
    }
    render(){
        if(this.props.reservation.shop === undefined || this.props.reservation.service === undefined) location.href = '../reservation/0';
        const reservation = this.props.reservation,
              disabled = (!reservation.date || !reservation.time);

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
                    }):<p>{this.state.hint}</p>}
                </div>
            </Col>
            <Button currentStep={1} clickHandle={this.props.nextStep} disabled={disabled}/>
            </Row>
            </Grid>
        );
    }
}

const mapStateToProps = (state)=>{
    return {
        loading: state.loading,
        reservation: state.reservation,
        sourceData: state.sourceData
    }
}

const mapDispatchToProps = (dispatch)=>{
    return bindActionCreators({
        toggleLoading: toggleLoading,
        setReservation: setReservation,
        setSourceData: setSourceData,
        clearSourceData: clearSourceData
    },dispatch);
  }
  
CheckTime = connect(mapStateToProps,mapDispatchToProps)(CheckTime);  

module.exports = CheckTime;