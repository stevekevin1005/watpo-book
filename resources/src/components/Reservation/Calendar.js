import { translate } from 'react-i18next';
import {connect} from "react-redux";

class Calendar extends React.Component{
    constructor(props){
        super(props);
        const date = new Date(),
              firstDay = new Date(date.getFullYear(),date.getMonth(),1),
              lastDayOfMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
        this.state = {
            displayingYear: date.getFullYear(),
            displayingMonth: date.getMonth() + 1, // 1-based
            firstWeekDay: firstDay.getDay(), // 1-based
            dayNum: lastDayOfMonth, // 0-based, 0=>禮拜天, 1=>禮拜一...

            selectedDay: -1 // 1-based
        };
        this.changeMonth = this.changeMonth.bind(this);
        this.selectDay = this.selectDay.bind(this);
    }
    changeMonth(date){

        // check if date if later than current date
        if(date.getTime() < new Date(new Date().getFullYear(), new Date().getMonth()).getTime()) return;
        if(date.getMonth > 11 || date.getMonth < 0) return;

        const firstDay = new Date(date.getFullYear(),date.getMonth(),1),
            lastDayOfMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
        
        // setData
        this.setState({
            displayingYear: date.getFullYear(),
            displayingMonth: date.getMonth() + 1,
            firstWeekDay: firstDay.getDay(),
            dayNum: lastDayOfMonth
        });
    }
    selectDay(){
        // send in month, day arguments
        this.props.selectDay(this.state.DisplayingMonth,this.state.selectedDay);
    }
    render(){
        const { t } = this.props;

        const months = [t("jan"),t("feb"),t("mar"),t("apr"),t("may"),t("jun"),t("jul"),t("aug"),t("sep"),t("oct"),t("nov"),t("dec")],
              weekDays = [t("mon"),t("tue"),t("wed"),t("thu"),t("fri"),t("sat"),t("sun")];
        const days = [], spanStyle = {display:"inline-block",width:"calc(100% / 7)"},
              firstDayStyle = {
                  display:"inline-block",
                  width:"calc(100% / 7)",
                  marginLeft: this.state.firstWeekDay?"calc(calc(100% / 7) * " + (this.state.firstWeekDay - 1) + ")"
                  :"calc(calc(100% / 7) * 6"};
        for(let i = 1;i <= this.state.dayNum; i++){
            if(i===1) days.push(<span key={i} style={firstDayStyle}>{i}</span>);
            else days.push(<span key={i} className="day" style={spanStyle}>{i}</span>);
        }

        return (<div className="calendar">
            <p className="year">{this.state.displayingYear}</p>
            <p className="month">
            <span onClick={()=>{
                let prevMonth = this.state.displayingMonth - 2; // 0-based
                console.log("displayingYear: "+this.state.displayingYear);
                console.log("Year passed:"+prevMonth <= 0 ?this.state.displayingYear-1:this.state.displayingYear);
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

module.exports = translate()(Calendar);