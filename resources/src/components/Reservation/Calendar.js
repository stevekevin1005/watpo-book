import { translate } from 'react-i18next';
import {connect} from "react-redux";
import clearSourceData from "../../dispatchers/clearSourceData";
import clearReservation from "../../dispatchers/clearReservation";
import {bindActionCreators} from "redux";

class Calendar extends React.Component{
    constructor(props){
        super(props);
        const date = new Date(),
              firstDay = new Date(date.getFullYear(),date.getMonth(),1),
              lastDayOfMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate(),
              inputtedDate = this.props.reservation.date?this.props.reservation.date.split("/"):null;

        this.state = {
            displayingYear: inputtedDate?inputtedDate[0]:date.getFullYear(),
            displayingMonth: inputtedDate?inputtedDate[1]:date.getMonth() + 1, // 1-based
            firstWeekDay: firstDay.getDay(), // 1-based
            dayNum: lastDayOfMonth, // 0-based, 0=>禮拜天, 1=>禮拜一...
        };

        this.changeMonth = this.changeMonth.bind(this);
        this.selectDay = this.selectDay.bind(this);
    }
    changeMonth(date){
        // check if date if later than current date
        if(date.getTime() < new Date(new Date().getFullYear(), new Date().getMonth()).getTime()) return;
        if(date.getMonth > 11 || date.getMonth < 0) return;

        this.setState({selectedDay: -1});
        const firstDay = new Date(date.getFullYear(),date.getMonth(),1),
            lastDayOfMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
        
        // set local state
        this.setState({
            displayingYear: date.getFullYear(),
            displayingMonth: date.getMonth() + 1,
            firstWeekDay: firstDay.getDay(),
            dayNum: lastDayOfMonth
        });

        // clear current displaying time periods
        this.props.clearSourceData("timeList");
        this.props.clearSourceData("selectedDetail");
        this.props.clearReservation("step1");
    }
    selectDay(event){
        // send in year, month, day arguments
        this.props.getTimePeriods(this.state.displayingYear, parseInt(this.state.displayingMonth), parseInt(event.target.innerHTML));
    }
    render(){
        const { t } = this.props, selectedDay = this.props.reservation.date?parseInt(this.props.reservation.date.split("/")[2]):-1;

        const months = [t("jan"),t("feb"),t("mar"),t("apr"),t("may"),t("jun"),t("jul"),t("aug"),t("sep"),t("oct"),t("nov"),t("dec")],
              weekDays = [t("mon"),t("tue"),t("wed"),t("thu"),t("fri"),t("sat"),t("sun")],
              unit = 100 / 7,
              isCurrentMonth = (this.state.displayingMonth == new Date().getMonth() + 1) &&(this.state.displayingYear == new Date().getFullYear()),
              today = new Date().getDate();
        const days = [], spanStyle = {display:"inline-block",width: unit + "%"},
              firstDayStyle = {
                  display:"inline-block",
                  width: unit + "%",
                  marginLeft: this.state.firstWeekDay?(this.state.firstWeekDay - 1) * unit + "%"
                  :unit * 6 +"%"};

        for(let i = 1;i <= this.state.dayNum; i++){
                let isPastDay = isCurrentMonth && i < today;
                if(i===1) days.push(<span key={i} className={i===selectedDay?"day selectedDay":(isPastDay?"day pastDay":"day")} style={firstDayStyle} onClick={isPastDay?null:this.selectDay}>{i}</span>);
                else days.push(<span key={i} className={i===selectedDay?"day selectedDay":(isPastDay?"day pastDay":"day")} style={spanStyle} onClick={isPastDay?null:this.selectDay}>{i}</span>);
        }

        return (<div className="calendar">
            <p className="year">{this.state.displayingYear}</p>
            <p className="month">
            <span onClick={()=>{
                let prevMonth = this.state.displayingMonth - 2; // 0-based

                this.changeMonth(new Date(
                    prevMonth < 0 ?this.state.displayingYear-1:this.state.displayingYear,
                    prevMonth < 0 ?prevMonth + 12:prevMonth
                ));
            }} className="prev"><i className="fa fa-angle-left" aria-hidden="true"></i></span>
            {months[this.state.displayingMonth - 1]}
            <span onClick={()=>{
                let nextMonth = this.state.displayingMonth; // 0-based

                this.changeMonth(new Date(
                    nextMonth >= 12?this.state.displayingYear + 1:this.state.displayingYear,
                    nextMonth >= 12? 0 :nextMonth
                ));
            }} className="next">
            <i className="fa fa-angle-right" aria-hidden="true"></i>
            </span>
            </p>
            <p className="weekDays">{weekDays.map((day,index)=>{
                return (<span key={index} style={spanStyle}>{day}</span>);
            })}</p>
            <p className="days">{days}</p>
            </div>);
    }
}

const mapStateToProps = (state)=>{
    return {
        reservation: state.reservation
    }
}

const mapDispatchToProps = (dispatch)=>{
    return bindActionCreators({
        clearSourceData: clearSourceData,
        clearReservation: clearReservation
    },dispatch);
}
  
Calendar = connect(mapStateToProps,mapDispatchToProps)(Calendar);  

module.exports = translate()(Calendar);