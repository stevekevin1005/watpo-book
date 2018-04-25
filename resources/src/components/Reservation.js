// 負責渲染和清理資料

import Steps from "./Reservation/Steps";
import Step from "./Reservation/Step";
import Alert from "./Reservation/Alert";
import CheckDetail from "./Reservation/checkDetail";
import CheckService from "./Reservation/checkService";
import CheckTime from "./Reservation/checkTime";
import { Link, Route } from 'react-router-dom';
import { translate } from 'react-i18next';

import { connect } from "react-redux";
import { bindActionCreators } from "redux";
import LoadingAnimation from "./LoadingAnimation";
import clearCheckOrdersInfo from "../dispatchers/clearCheckOrdersInfo";
import { renderToStaticMarkup } from 'react-dom/server';


const Grid = ReactBootstrap.Grid,
    Row = ReactBootstrap.Row,
    Col = ReactBootstrap.Col;
import SweetAlert from 'sweetalert-react';

class Reservation extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            showAlert: false,
            alertTitle: "",
            alertText: "",
            success: false,

            reservation: {
                shop: null,
                service: null,

                guestNum: 0,
                operator: [],
                operator_text: [],
                roomId: null,
                name: null,
                contactNumber: null,

                date: null,
                time: null,

                shower: false
            },

            sourceData: {
                shops: null,
                services: null,
                timeList: null,
                service_provider_list: null,
                room: null
            },

            loading: false
        };

        this.setReservation = this.setReservation.bind(this);
        this.clearData = this.clearData.bind(this);
        this.setSourceData = this.setSourceData.bind(this);
        this.clearSourceData = this.clearSourceData.bind(this);
        this.send = this.send.bind(this);
        this.showErrorPopUp = this.showErrorPopUp.bind(this);
        this.toggleLoading = this.toggleLoading.bind(this);
    }
    componentDidMount() {
        if (this.props.checkOrdersInfo != {}) {
            this.props.clearCheckOrdersInfo("name");
            this.props.clearCheckOrdersInfo("contactNumber");
        }
    }
    toggleLoading() {
        this.setState({ loading: !this.state.loading });
    }
    setSourceData(data, callback) {
        let sourceData = JSON.parse(JSON.stringify(this.state.sourceData));

        Object.keys(data).forEach((key, i) => {
            sourceData[key] = data[key];
        });

        this.setState({
            sourceData
        }, () => {
            if (callback) callback();
        });
    }
    setReservation(data, callback) {
        let reservation = JSON.parse(JSON.stringify(this.state.reservation));

        Object.keys(data).forEach((key, i) => {
            reservation[key] = data[key];
        });

        this.setState({
            reservation
        }, () => {
            if (callback) callback();
        });
    }
    clearSourceData(key) {
        this.toggleLoading();
        const sourceData = JSON.parse(JSON.stringify(this.state.sourceData));
        sourceData[key] = null;
        this.setState({
            sourceData
        }, () => {
            if (this.state.loading) this.setState({ loading: false });
        });
    }
    clearData(step) {
        let sourceData = JSON.parse(JSON.stringify(this.state.sourceData)),
            reservation = JSON.parse(JSON.stringify(this.state.reservation));

        switch (step) {
            case 0:
                this.toggleLoading();
                sourceData["service_provider_list"] = null;
                sourceData["room"] = null;
                const newReservationData = {
                    service: reservation.service,
                    shop: reservation.shop,

                    guestNum: null,
                    shower: null,
                    operator: [],
                    roomId: null,
                    name: null,
                    contactNumber: null,

                    date: null,
                    time: null
                };
                this.setState({
                    sourceData,
                    reservation: newReservationData
                }, () => {
                    if (this.state.loading) this.setState({ loading: false });
                });
                break;
            case 1:
                this.toggleLoading();
                reservation["date"] = null;
                reservation["time"] = null;
                sourceData["timeList"] = null;
                this.setState({ sourceData, reservation }, () => {
                    if (this.state.loading) this.setState({ loading: false });
                });
                break;
            case 2:
        }
    }
    send() {
        const { t } = this.props;

        // get info: service, shop
        const reservation = this.state.reservation,
            serviceIndex = this.state.sourceData.services.reduce((result, service, index) => { return result + (service.id == reservation.service ? index : 0) }, 0),
            serviceName = this.state.sourceData.services[serviceIndex].title;

        // get end time
        const duration = this.state.sourceData.services[serviceIndex].time / 60,
            token = document.querySelector('input[name="_token"]').value,
            that = this;
        let endTime = reservation.time.split(":");
        endTime[0] = parseInt(endTime[0]) + duration;
        endTime = (endTime[0] >= 10 ? endTime[0] : "0" + endTime[0]) + ":" + endTime[1] + ":" + endTime[2];
        let date = reservation.date;

        // 確認是否需要將日期改為隔日
        if (reservation.time[0] === "0") {
            // 是過凌晨00:00:00的時間，故調整日期
            let newDate = date.split("/").map((val) => {
                return parseInt(val);
            }),
                daynum = new Date(newDate[0], newDate[1], 0).getDate(); //該月最後一天日期
            //跨月
            if (newDate[2] == daynum) {
                newDate[1] = newDate[1] + 1;
                newDate[2] = 1;
            }
            //跨年
            else if (newDate[1] == 12 && newDate[2] == 31) {
                newDate[0] = newDate[0] + 1;
                newDate[1] = 1;
                newDate[2] = 1;
            } else {
                newDate[2] = newDate[2] + 1;
            }
            if (newDate[1] < 10) newDate[1] = "0" + newDate[1];
            if (newDate[2] < 10) newDate[2] = "0" + newDate[2];
            newDate = newDate.join("/");
            date = newDate;
        }

        // call API
        this.toggleLoading();
        axios({
            method: "post",
            url: "/api/order",
            params: {
                phone: reservation.contactNumber,
                shop_id: reservation.shop,
                service_id: reservation.service,
                start_time: date + " " + reservation.time,
                end_time: date + " " + endTime,
                room_id: reservation.roomId,
                person: reservation.guestNum,
                service_provider_id: reservation.operator.join(),
                name: reservation.name,
                shower: reservation.shower
            },
            headers: { 'X-CSRF-TOKEN': token },
            responseType: 'json'
        }).then(function (response) {
            if (response.statusText == "OK") {
                // show success alert
                let operator_text = "";
                reservation.operator_text.forEach(function (operator) {
                    operator_text += (" " + operator);
                });

                that.toggleLoading();
                that.setState({
                    success: true,
                    showAlert: true,
                    alertTitle: t("reserveSuccess"),
                    alertText: <Alert notice={t("reserveNotice2")} text={t("reservatorName") + ": " + reservation.name + "\n" + t("contactNumber") + ": " + reservation.contactNumber + "\n" + t("reservatorDate") + ": " + reservation.date + " " + reservation.time + "\n服務: " + serviceName + "\n人數: " + reservation.guestNum + " " + (reservation.guestNum > 1 ? t("people") : t("person")) + " " + t("operator") + ": " + operator_text + "\n" + t("reserveNotice1") + "\n" + t("reserveNotice3")} />
                });
            } else {
                // show failure alert
                that.toggleLoading();
                that.setState({
                    showAlert: true,
                    alertTitle: t("error"),
                    alertText: t("errorHint_system")
                });
            }
        }).catch(function (error) {
            console.log(error);
            // error handle
            that.toggleLoading();
            that.showErrorPopUp();
        });
    }
    showErrorPopUp() {
        const { t } = this.props;
        this.setState({
            showAlert: true,
            alertTitle: t("error"),
            alertText: t("errorHint_system")
        });
    }
    render() {
        return (
            <Grid>
                <div className="reservationContainer">
                    <Row className="reservationGrid">
                        <div className="reservationContent" style={{ padding: "16px 0" }}>
                            <Step
                                {...this.props}
                                saveReservationAndSourceData={this.saveReservationAndSourceData}
                                setReservation={this.setReservation}
                                setSourceData={this.setSourceData}
                                clearSourceData={this.clearSourceData}
                                send={this.send}
                                clearData={this.clearData}
                                showErrorPopUp={this.showErrorPopUp}
                                toggleLoading={this.toggleLoading}

                                reservation={this.state.reservation}
                                sourceData={this.state.sourceData}
                                loading={this.state.loading}
                            />
                        </div>
                        {this.state.loading && <Col md={12}><LoadingAnimation /></Col>}
                    </Row>
                </div>
                {/* Enter SMS verification code */}

                <SweetAlert
                    show={this.state.showAlert}
                    title={this.state.alertTitle}
                    html
                    text={typeof this.state.alertText == "object" ? renderToStaticMarkup(this.state.alertText) : this.state.alertText}
                    onConfirm={() => {
                        this.setState({ showAlert: false });
                        if (this.state.loading) this.setState({ loading: false });
                        if (this.state.success) location.reload();
                    }}
                />
            </Grid>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        checkOrdersInfo: state.checkOrdersInfo
    }
}

const mapDispatchToProps = (dispatch) => {
    return bindActionCreators({
        clearCheckOrdersInfo: clearCheckOrdersInfo
    }, dispatch);
}

Reservation = connect(mapStateToProps, mapDispatchToProps)(Reservation);

module.exports = translate()(Reservation);