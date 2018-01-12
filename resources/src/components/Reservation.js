// 負責渲染和清理資料

import CheckDetail from "./Reservation/checkDetail";
import CheckService from "./Reservation/checkService";
import CheckTime from "./Reservation/checkTime";
import { Link } from 'react-router-dom';
import { translate } from 'react-i18next';
import {connect} from "react-redux";
import {bindActionCreators} from "redux";
import LoadingAnimation from "./LoadingAnimation";
import clearReservation from "../dispatchers/clearReservation";
import clearSourceData from "../dispatchers/clearSourceData";
import toggleLoading from "../dispatchers/toggleLoading";
import clearCheckOrdersInfo from "../dispatchers/clearCheckOrdersInfo";

const Grid = ReactBootstrap.Grid,
    Row = ReactBootstrap.Row,
    Col = ReactBootstrap.Col;
import SweetAlert from 'sweetalert-react';

class Reservation extends React.Component{
    constructor(props){
        super(props);

        this.state = {
            showAlert: false,
            alertTitle: "",
            alertText: "",
            success: false
        };
        
        this.nextStep = this.nextStep.bind(this);
        this.send = this.send.bind(this);
    }
    componentWillReceiveProps(nextProps){

        // 清資料
        const reservation = this.props.reservation;
        switch(nextProps.match.params.step){
            case "0":
                // step1 => step0
                if(reservation.time!=undefined || reservation.date!=undefined){
                    this.props.clearReservation("step1");
                    this.props.clearSourceData("selectedDetail");
                    this.props.clearSourceData("timeList");
                    if(((reservation.operator.length>2 || reservation.room!=undefined) || (reservation.guestNum > 1 ||reservation.name!=undefined)) || reservation.contactNumber!=undefined){
                        this.props.clearReservation("step2");
                    } 
                }
                break;
            case "1":
                // step2 => step1
                if(((reservation.operator.length>2 || reservation.room!=undefined) || (reservation.guestNum > 1 ||reservation.name!=undefined)) || reservation.contactNumber!=undefined){
                    this.props.clearReservation("step2");
                } 
                break;
            case "2":
                if(nextProps.sourceData.timeList === undefined || nextProps.sourceData.selectedDetail === undefined)
                    this.props.history.push('/reservation/0');
        }
    }
    componentDidMount(){
        if(this.props.checkOrdersInfo != {}){
            this.props.clearCheckOrdersInfo("name");
            this.props.clearCheckOrdersInfo("contactNumber");
        }
    }
    nextStep(){
        this.props.history.push('/reservation/' + (parseInt(this.props.match.params.step) + 1));
    }
    send(){
        const { t } = this.props;
      
      // get info: service, shop
      const reservation = this.props.reservation,
            serviceName = this.props.sourceData.services[reservation.service].title;

      // check if there's available room
      if(reservation.room === undefined){
        that.setState({
            showAlert: true,
            alertTitle: t("error"),
            alertText: t("errorHint_noRoom")
        });
        return;
      }
    // get end time
    const duration = this.props.sourceData.services[this.props.reservation.service].time / 60,
        token = document.querySelector('input[name="_token"]').value,
        that = this;
    let endTime = reservation.time.split(":");
        endTime[0] = parseInt(endTime[0]) + duration;
        endTime = (endTime[0]>=10?endTime[0]:"0"+endTime[0]) + ":" + endTime[1] + ":" + endTime[2];
    let date = reservation.date;

      // 確認是否需要將日期改為隔日
      if(reservation.time[0]==="0"){
            // 是過凌晨00:00:00的時間，故調整日期
            let newDate = date.split("/").map((val)=>{
                return parseInt(val);
            }),
            daynum = new Date(newDate[0], newDate[1], 0).getDate(); //該月最後一天日期
            //跨月
            if(newDate[2]==daynum){
                newDate[1]=newDate[1]+1;
                newDate[2]=1;
            }
            //跨年
            else if(newDate[1]==12&&newDate[2]==31){
                newDate[0]=newDate[0]+1;
                newDate[1]=1;
                newDate[2]=1;
            }else{
                newDate[2]=newDate[2]+1;
            }
            if(newDate[1] < 10) newDate[1] = "0"+newDate[1];
            if(newDate[2] < 10) newDate[2] = "0"+newDate[2];
            newDate = newDate.join("/");
            date = newDate;
      }
      console.log(date);
      // call API
      this.props.toggleLoading(true);
      axios({
          method: "post",
          url: "/api/order",
          params: {
              phone: reservation.contactNumber,
              shop_id: this.props.sourceData.shops[reservation.shop].id,
              service_id: this.props.sourceData.services[reservation.service].id,
              start_time: date + " " + reservation.time,
              end_time: date + " " + endTime,
              room_id: reservation.room,
              person: reservation.guestNum,
              service_provider_id: reservation.operator.join(),
              name: reservation.name
          },
          headers: {'X-CSRF-TOKEN': token},
          responseType: 'json'
      }).then(function(response){
          if(response.statusText == "OK"){
              // show success alert
              that.props.toggleLoading(false);
              that.setState({
                  success: true,
                  showAlert: true,
                  alertTitle: t("success"),
                  alertText: reservation.name + " " + reservation.date + " " + reservation.time + " " + serviceName + " " + reservation.guestNum + " "+ (reservation.guestNum>1?t("people"):t("person")) +" " + t("success")
              });
          }else{
              // show failure alert
              that.props.toggleLoading(false);
              that.setState({
                  showAlert: true,
                  alertTitle: t("error"),
                  alertText: t("errorHint_system")
              });
          }
      }).catch(function(error){
          console.log(error);
          // error handle
          that.props.toggleLoading(false);
          that.setState({
              showAlert: true,
              alertTitle: t("error"),
              alertText: t("errorHint_system")
          });
      });
    }
    render(){
        const { t } = this.props;

        // set up steps
        const currentStep = parseInt(this.props.match.params.step),
              stepsData = [t("chooseService"),t("chooseTime"),t("checkDetails")], pointer = {cursor: "pointer"}, currentStepStyle = {cursor:"pointer",color: "#914327"};

        let steps = stepsData.map((step, index,arr)=>{
                let divider = index < arr.length - 1 && <span> <i className="fa fa-angle-right" aria-hidden="true"></i> </span>;
                if(currentStep > index)return (
                    <span key={index}>
                        <Link to={"/reservation/"+index}>
                            <span 
                                style = {currentStep === index?currentStepStyle: null}
                            >{step}</span>
                        </Link>
                    {divider}</span>);
                return (
                    <span>
                            <span 
                                style = {currentStep === index?currentStepStyle: null}
                            >{step}</span>
                    {divider}</span>);
              });

        // content to show
        let el;
        switch(currentStep){
            case 0:
                el = (
                    <CheckService nextStep={this.nextStep}/>);
                break;
            case 1:
                el = <CheckTime nextStep={this.nextStep}/>;
                break;
            case 2:
                el = <CheckDetail send={this.send}/>;
                break;
            default:
                return;
        }

        return(
            <Grid>
            <div className="reservationContainer">
                <Row className="reservationGrid">
                <Col md={12}>
                    <div className="steps">
                        {steps}
                    </div>
                </Col>
                {currentStep > 0 && <Col md={12} >
                <p className="prevStap"><Link to={"/reservation/"+ (currentStep - 1)}><span><i className="fa fa-angle-left" aria-hidden="true"></i>{" "+t("prevStep")}</span></Link></p>
                </Col>}
                    <div className="reservationContent" style={{padding:"16px 0"}}>
                        {el}
                    </div>    
                {this.props.loading && <Col md={12}><LoadingAnimation /></Col>}
                </Row>                
                </div>
                <SweetAlert
                    show={this.state.showAlert}
                    title={this.state.alertTitle}
                    text={this.state.alertText}
                    onConfirm={() => {
                        this.setState({ showAlert: false });
                        if(this.state.success) location.reload();
                    }}
                />
            </Grid>
        );
    }
}

const mapStateToProps = (state)=>{
    return {
        loading: state.loading,
        reservation: state.reservation,
        sourceData: state.sourceData,
        checkOrdersInfo: state.checkOrdersInfo
    }
}

const mapDispatchToProps = (dispatch)=>{
    return bindActionCreators({
        clearReservation: clearReservation,
        clearSourceData: clearSourceData,
        clearCheckOrdersInfo: clearCheckOrdersInfo,
        toggleLoading: toggleLoading
    },dispatch);
}
  
Reservation = connect(mapStateToProps, mapDispatchToProps)(Reservation);

module.exports = translate()(Reservation);