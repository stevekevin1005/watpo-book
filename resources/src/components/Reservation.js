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
import { browserHistory } from 'react-router'

import { UserVerifiy } from '../actions'


const Grid = ReactBootstrap.Grid,
    Row = ReactBootstrap.Row,
    Col = ReactBootstrap.Col;
import SweetAlert from 'sweetalert-react';

let Null_Package = {
    service: 1,
    guestNum: 1,
    operator: [0],
    operator_text: ['不指定'],
    roomId: null,
    shower: false
}

class Reservation extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            resetTime: 60 * 10,
            time: 0,
            showTimeOut: false,
            showAlert: false,
            alertTitle: "",
            alertText: "",
            success: false,

            reservation: {
                shop: null,
                name: null,
                contactNumber: null,
                total_guest_num: null,
                date: null,
                time: null,
                unarranged_people: 1,
                service_provider_list: null


            },
            package_reservation: [
                // {
                //     service: [1],
                //     guestNum: 1,
                //     operator: [0],
                //     operator_text: ['不指定'],
                //     roomId: null,
                //     shower: false,
                //     room_list: null
                // }
            ],

            sourceData: {
                shops: null,
                services: null,
                timeList: null,
                room_list: null

            },

            loading: false
        };

        this.setPackageReservation = this.setPackageReservation.bind(this)
        this.appendNewPackage = this.appendNewPackage.bind(this)
        this.setReservation = this.setReservation.bind(this);
        this.clearData = this.clearData.bind(this);
        this.setSourceData = this.setSourceData.bind(this);
        this.clearSourceData = this.clearSourceData.bind(this);
        this.send = this.send.bind(this);
        this.showErrorPopUp = this.showErrorPopUp.bind(this);
        this.toggleLoading = this.toggleLoading.bind(this);
        this.removePackage = this.removePackage.bind(this);
        this.claerPackage = this.claerPackage.bind(this);
        this.setFormTimeOut = this.setFormTimeOut.bind(this)
        this.getRestTime = this.getRestTime.bind(this)
        this.setFormTimeOut()
    }

    setFormTimeOut() {
        setInterval(() => {
            let temp_time = this.state.time
            if (temp_time == this.state.resetTime) {
                this.setState({
                    showTimeOut: true,
                    time: 0
                })
            }
            else
                this.setState({
                    time: temp_time + 1
                })
        }, 1000);
        // setTimeout(() => {
        //     this.setState({
        //         showTimeOut: true
        //     })
        // }, 1000 * 60 * 5)

        // 1000ms * 60sec * 5 min
    }

    getRestTime(t) {
        let min = 0, sec = 0
        let total_time = this.state.resetTime
        let sec_str = ''
        min = parseInt((total_time - this.state.time) / 60)

        sec = (total_time - this.state.time) - (min * 60)
        if (sec / 10 < 1) {
            sec_str = '0' + sec
        }
        else {
            sec_str = '' + sec
        }
        return min + ':' + sec_str
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

    appendNewPackage(cb) {
        let temp_list = this.state.package_reservation
        // let null_pkg = {
        //     service: 1,
        //     guestNum: 1,
        //     operator: [0],
        //     operator_text: ['不指定'],
        //     roomId: null,
        //     shower: false
        // }
        temp_list = [...temp_list, {
            service: [1],
            guestNum: 1,
            operator: [0],
            operator_text: ['不指定'],
            roomId: null,
            // service_provider_list: null,
            shower: false
        }]
        this.setState({
            package_reservation: temp_list
        })
    }

    removePackage(no) {
        let temp_list = this.state.package_reservation
        // let null_pkg = Null_Package
        // temp_list = [...temp_list, Null_Package]
        if (no < temp_list.length) {
            delete temp_list[no]
            temp_list = temp_list.filter((el) => { return el })
            console.log("temp_list: ", temp_list)
            this.setState({
                package_reservation: temp_list
            })
        }

    }
    claerPackage() {
        this.setState({
            package_reservation: [],
            unarranged_people: this.state.total_guest_num
        })
    }
    setPackageReservation(no, data, callback) {
        let temp_list = this.state.package_reservation
        console.log("Package list:", temp_list)
        if (temp_list.length - 1 >= no) {
            Object.keys(data).forEach((key, i) => {
                temp_list[no][key] = data[key];
            });
            this.setState({
                package_reservation: temp_list
            })
        }
        if (callback)
            callback()
        // if (no - temp_list.length - 1 == 1) {
        //     let null_pkg = Null_Package
        //     Object.keys(data).forEach((key, i) => {
        //         null_pkg[key] = data[key];
        //     });
        //     temp_list.push(null_pkg)
        //     this.setState({
        //         package_reservation: temp_list
        //     })
        // }

        // if (data.service && data.guestNum && data.operator && data.operator_text && data.roomId && data.shower !== undefined) {
        //     let temp_list = this.state.package_reservation
        //     temp_list.push(data)
        //     this.setState({
        //         package_reservation: temp_list
        //     })
        // }
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
            reservation = JSON.parse(JSON.stringify(this.state.reservation)),
            package_reservation = JSON.parse(JSON.stringify(this.state.package_reservation));

        switch (step) {
            case 0:
                this.toggleLoading();
                package_reservation = [];
                reservation["service_provider_list"] = null;
                // sourceData["room"] = null;
                const newReservationData = {

                    shop: null,
                    name: null,
                    contactNumber: null,
                    total_guest_num: null,
                    date: null,
                    time: null,
                    unarranged_people: 1,
                    service_provider_list: null

                    // service: reservation.service,
                    // shop: reservation.shop,

                    // guestNum: null,
                    // shower: null,
                    // operator: [],
                    // roomId: null,
                    // name: null,
                    // contactNumber: null,

                    // date: null,
                    // time: null
                };
                this.setState({
                    sourceData,
                    reservation: newReservationData,
                    package_reservation
                }, () => {
                    if (this.state.loading) this.setState({ loading: false });
                });
                break;
            case 1:
                this.toggleLoading();
                package_reservation = [];
                reservation["date"] = null;
                reservation["time"] = null;
                sourceData["timeList"] = null;
                this.setState({ sourceData, reservation, package_reservation }, () => {
                    if (this.state.loading) this.setState({ loading: false });
                });
                break;
            case 2:
        }
    }
    send() {
        const { t } = this.props;
        // get info: service, shop
        const { room_list } = this.state.sourceData
        const { room_id } = this.state.reservation
        const reservation = this.state.reservation,
            // serviceIndex = this.state.sourceData.services.reduce((result, service, index) => { return result + (service.id == reservation.service ? index : 0) }, 0),
            // serviceName = this.state.sourceData.services[serviceIndex].title,
            package_reservation = this.state.package_reservation;
        // get end time
        let serviceName = ""
        package_reservation.forEach((current_package) => {
            current_package.service.forEach((s) => {
                serviceName += this.state.sourceData.services[s-1].title + ","

            })
        })
        // package_reservation[res_status].operator_text.forEach(function (operator) {
        //     operator_text += (" " + operator);
        // });
        // for (let i = 0; i < package_reservation.length; i++) {
        //     if (i == package_reservation.length - 1)
        //         serviceName += this.state.sourceData.services[package_reservation[i].service].title
        //     else
        //         serviceName += this.state.sourceData.services[package_reservation[i].service].title + ","
        //     // if (duration < (this.state.sourceData.services[package_reservation[i]].time / 60))
        //     //     duration = (this.state.sourceData.services[package_reservation[i]].time / 60)
        // }
        // // const duration = this.state.sourceData.services[serviceIndex].time / 60,
        const token = document.querySelector('input[name="_token"]').value,
            that = this;

        let date = reservation.date;

        // // 確認是否需要將日期改為隔日
        // if (reservation.time[0] === "0") {
        //     // 是過凌晨00:00:00的時間，故調整日期
        //     let newDate = date.split("/").map((val) => {
        //         return parseInt(val);
        //     }),
        //         daynum = new Date(newDate[0], newDate[1], 0).getDate(); //該月最後一天日期
        //     //跨月
        //     if (newDate[2] == daynum) {
        //         newDate[1] = newDate[1] + 1;
        //         newDate[2] = 1;
        //     }
        //     //跨年
        //     else if (newDate[1] == 12 && newDate[2] == 31) {
        //         newDate[0] = newDate[0] + 1;
        //         newDate[1] = 1;
        //         newDate[2] = 1;
        //     } else {
        //         newDate[2] = newDate[2] + 1;
        //     }
        //     if (newDate[1] < 10) newDate[1] = "0" + newDate[1];
        //     if (newDate[2] < 10) newDate[2] = "0" + newDate[2];
        //     newDate = newDate.join("/");
        //     date = newDate;
        // }

        // call API
        this.toggleLoading();
        let pacakge_time_room_promise = []
        for (let package_no = 0; package_no < package_reservation.length; package_no++) {
            let current_package = package_reservation[package_no]
            let endTime = reservation.time.split(":");
            let max_time = 0;
            for (let i = 0; i < current_package.service.length; i++) {
                let time = this.state.sourceData.services[current_package.service[i] - 1].time
                if (max_time < time) {
                    max_time = time
                }
            }

            console.log('End time:', endTime)
            endTime[0] = parseInt(endTime[0]) + (max_time / 60);
            endTime = (endTime[0] >= 10 ? endTime[0] : "0" + endTime[0]) + ":" + endTime[1] + ":00"// + endTime[2];
            let service_pair = {}
            current_package.service.map((val, idx) => {
                if (!service_pair[val]) {
                    service_pair[val] = [current_package.operator[idx]]
                }
                else {
                    service_pair[val].push(current_package.operator[idx])
                }
            })
            console.log('service_pair:', service_pair)
            console.log('reservation:', reservation)
            pacakge_time_room_promise.push(axios({
                method: "post",
                url: "/api/order",
                params: {
                    phone: reservation.contactNumber,
                    shop_id: reservation.shop,
                    service_pair: service_pair,
                    // service_id: 1,//current_package.service[0],
                    start_time: date + " " + reservation.time,
                    end_time: date + " " + endTime,
                    room_id: room_id[package_no],//current_package.roomId,
                    person: current_package.guestNum,
                    // service_provider_id: current_package.operator.join(),
                    name: reservation.name,
                    shower: current_package.shower
                },
                headers: { 'X-CSRF-TOKEN': token },
                responseType: 'json'
            }))
        }
        axios.all(pacakge_time_room_promise).then(response => {
            let operator_text = "";
            let success_package = 1, package_desc = ''
            for (let res_status = 0; res_status < response.length; res_status++) {
                if (response[res_status].statusText == "OK") {
                    // show success alert
                    package_desc += '包廂' + success_package + ':'

                    package_reservation[res_status].operator_text.forEach(function (operator, idx) {
                        package_desc += that.state.sourceData.services[package_reservation[res_status].service[idx] - 1].title + '\n' + operator + '\n';
                        // operator_text += (" " + operator);
                    });
                    package_desc += '---------------------\n'
                    success_package += 1;
                } else {
                    // show failure alert
                    that.toggleLoading();
                    that.setState({
                        showAlert: true,
                        alertTitle: t("error"),
                        alertText: t("errorHint_system")
                    });
                }
            }

            that.toggleLoading();

            let package_shower_cnt = 0
            package_reservation.forEach((x) => {
                if (x.shower) {
                    package_shower_cnt += 1
                }
            })

            that.setState({
                success: true,
                showAlert: true,
                alertTitle: t("reserveSuccess"),
                alertText: <Alert notice={t("reserveNotice2")} text={
                    t("locations") + ": " + (reservation.shop == 1 ? t("location1") : t("location2")) + "\n"
                    + t("registrationNumber") + ": " + (reservation.shop == 1 ? "02 2581-3338" : "02 2570-9393") + "\n"
                    + t("reservatorName") + ": " + reservation.name + "\n"
                    + t("contactNumber") + ": " + reservation.contactNumber + "\n"
                    + t("reservatorDate") + ": " + reservation.date + " " + reservation.time + "\n"
                    // + "服務: " + serviceName + "\n"
                    + package_desc
                    + "包廂數量:" + package_reservation.length + '( 沖澡:' + package_shower_cnt + ' )' + "\n"
                    + "人數: " + reservation.total_guest_num + " " + (reservation.total_guest_num > 1 ? t("people") : t("person")) + "\n"// + t("operator") + ": " + operator_text + "\n"
                    + t("reserveNotice1") + "\n"
                    + t("reserveNotice2") + '\n'
                    + t("reserveNotice3") + "\n" + "本店不可攜帶寵物"} />
            });


        }).catch(function (error) {
            console.log(error);
            // error handle
            that.toggleLoading();
            that.showErrorPopUp();
        });

        // axios({
        //     method: "post",
        //     url: "/api/order",
        //     params: {
        //         phone: reservation.contactNumber,
        //         shop_id: reservation.shop,
        //         service_id: reservation.service,
        //         start_time: date + " " + reservation.time,
        //         end_time: date + " " + endTime,
        //         room_id: reservation.roomId,
        //         person: reservation.guestNum,
        //         service_provider_id: reservation.operator.join(),
        //         name: reservation.name,
        //         shower: reservation.shower
        //     },
        //     headers: { 'X-CSRF-TOKEN': token },
        //     responseType: 'json'
        // }).then(function (response) {
        //     if (response.statusText == "OK") {
        //         // show success alert
        //         let operator_text = "";
        //         reservation.operator_text.forEach(function (operator) {
        //             operator_text += (" " + operator);
        //         });

        //         that.toggleLoading();
        //         that.setState({
        //             success: true,
        //             showAlert: true,
        //             alertTitle: t("reserveSuccess"),
        //             alertText: <Alert notice={t("reserveNotice2")} text={
        //                 t("locations") + ": " + (reservation.shop == 1 ? t("location1") : t("location2")) + "\n"
        //                 + t("registrationNumber") + ": " + (reservation.shop == 1 ? "02 2581-3338" : "02 2570-9393") + "\n"
        //                 + t("reservatorName") + ": " + reservation.name + "\n"
        //                 + t("contactNumber") + ": " + reservation.contactNumber + "\n"
        //                 + t("reservatorDate") + ": " + reservation.date + " " + reservation.time + "\n"
        //                 + "服務: " + serviceName + "\n"
        //                 + "人數: " + reservation.guestNum + " " + (reservation.guestNum > 1 ? t("people") : t("person")) + " " + t("operator") + ": " + operator_text + "\n"
        //                 + t("reserveNotice1") + "\n"
        //                 + t("reserveNotice3")} />
        //         });
        //     } else {
        //         // show failure alert
        //         that.toggleLoading();
        //         that.setState({
        //             showAlert: true,
        //             alertTitle: t("error"),
        //             alertText: t("errorHint_system")
        //         });
        //     }
        // }).catch(function (error) {
        //     console.log(error);
        //     // error handle
        //     that.toggleLoading();
        //     that.showErrorPopUp();
        // });
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
                    <Row>
                        <span className="intervalTime">剩餘時間 {this.getRestTime(this.state.time)}</span>
                    </Row>
                    <Row className="reservationGrid">
                        <div className="reservationContent" style={{ padding: "16px 0" }}>
                            <Step
                                {...this.props}
                                saveReservationAndSourceData={this.saveReservationAndSourceData}
                                setReservation={this.setReservation}
                                setPackageReservation={this.setPackageReservation}
                                // setTotalGuestNum={this.setTotalGuestNum}
                                setSourceData={this.setSourceData}
                                clearSourceData={this.clearSourceData}
                                send={this.send}
                                clearData={this.clearData}
                                showErrorPopUp={this.showErrorPopUp}
                                toggleLoading={this.toggleLoading}
                                appendNewPackage={this.appendNewPackage}
                                removePackage={this.removePackage}
                                claerPackage={this.claerPackage}
                                // total_guest_num={this.state.total_guest_num}

                                package_reservation={this.state.package_reservation}
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
                    show={this.state.showTimeOut}
                    title={'填表時間逾時'}
                    html
                    text={'預約時間為5分鐘，請於時間內填寫完畢'}
                    onConfirm={() => {
                        this.setState({ timeOut: 0, showTimeOut: false })
                        this.props.history.push('/')
                    }}
                />
                <SweetAlert
                    show={this.state.showAlert}
                    title={this.state.alertTitle}
                    html
                    text={typeof this.state.alertText == "object" ? renderToStaticMarkup(this.state.alertText) : this.state.alertText}
                    onConfirm={() => {
                        this.setState({ showAlert: false });
                        if (this.state.loading) this.setState({ loading: false });
                        if (this.state.success) this.props.history.push('/')
                    }}
                />
            </Grid>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        checkOrdersInfo: state.checkOrdersInfo,
        verifiy: state.phoneValidator.verifiy
    }
}

const mapDispatchToProps = (dispatch) => {
    return bindActionCreators({
        clearCheckOrdersInfo: clearCheckOrdersInfo
    }, dispatch);
}

Reservation = connect(mapStateToProps, mapDispatchToProps)(Reservation);

module.exports = translate()(Reservation);