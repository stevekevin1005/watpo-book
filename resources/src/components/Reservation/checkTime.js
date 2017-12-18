// 負責寫資料(日期,時段)到global state

import Calendar from "./Calendar";
import {connect} from "react-redux";
import {bindActionCreators} from "redux";
import toggleLoading from "../../dispatchers/toggleLoading";
import setReservation from "../../dispatchers/setReservation";
import setSourceData from "../../dispatchers/setSourceData";

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
        const that = this, date = year+"/"+month+"/"+day,
              csrf_token = document.querySelector('input[name="_token"]').value;
        if(!this.state.touched) this.setState({hint: ""});

        console.log(csrf_token);

        this.props.toggleLoading(true);
        this.props.setReservation("date", date);
        axios({
                method: "get",
                url: "../api/time_list",
                params: {
                    shop_id: this.props.reservation.shop,
                    service_id: this.props.reservation.service,
                    date: date
                },
                headers: {'X-CSRF-TOKEN': csrf_token},
                responseType: 'json'
            })
            .then(function (response) {
                console.log(response);
                if(response.statusText == "OK"){
                    that.props.setSourceData(response.data);
                    that.props.toggleLoading(false);
                    if(response.data.length === 0) that.setState({hint: "目前無符合時段"});
                }
            })
            .catch(function (error) {
                console.log(error);
                that.props.toggleLoading(false);
                that.setState({hint: "某處發生錯誤，請重新嘗試"});
            });
        
    }
    setTime(event){
        const value = event.target.innerHTML;
        console.log(value);
        this.props.setReservation("time", value);
    }
    render(){
        return(
            <Grid>
            <Row className="show-grid">
            <Col md={5}>
                <Calendar getTimePeriods={this.getTimePeriods}/>
            </Col>
            <Col md={5}>
                <div className="timePeriods">
                    {this.state.timePeriods?this.props.timeList.map((time,index)=>{
                        return (<span className="timePeriod" key={index} data-index={index} onClick={this.setTime}>{time.time}</span>);
                    }):<p>{this.state.hint}</p>}
                </div>
            </Col>
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
        setSourceData: setSourceData
    },dispatch);
  }
  
CheckTime = connect(mapStateToProps,mapDispatchToProps)(CheckTime);  

module.exports = CheckTime;