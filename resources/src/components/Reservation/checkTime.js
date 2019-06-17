// 負責寫資料(日期,時段)到global state
import { translate } from 'react-i18next';
import Calendar from "./Calendar";
import LoadingAnimation from "../LoadingAnimation";
import Button from "./Button";

const Col = ReactBootstrap.Col,
    ListGroupItem = ReactBootstrap.ListGroupItem,
    ListGroup = ReactBootstrap.ListGroup;

function condition_reason(shower, people) {
    let r = "無"
    if (shower == 1) {
        r += "沐浴用"
    }
    r += (people + "人房")
    return r
}

function removeRepeatArrElement(arr) {
    var newArr = [];
    var temp = [];
    for (var i in temp) {
        temp[arr[i]] = 1;
    }
    for (var i in temp) {
        newArr.push(i);
    }
    return newArr;
}



class CheckTime extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            hint: "calendarHint",
            longTimePeriodChoose: false,
            reason: [],
            time_can_select: []
        };

        this.union_time = this.union_time.bind(this)
        this.getTimePeriods = this.getTimePeriods.bind(this);
        this.setTime = this.setTime.bind(this);
        this.setDate = this.setDate.bind(this);
        this.clearDateAndTimeAndTimeList = this.clearDateAndTimeAndTimeList.bind(this);
        this.setLongTimePeriod = this.setLongTimePeriod.bind(this);
        if (this.props.reservation.shop == 1) {
            this.earlyMorning = "00:00 - 04:00";
            this.noon = "12:00 - 15:30";
            this.afternoon = "16:00 - 19:30";
            this.night = "20:00 - 23:30";
        }
        else {
            this.earlyMorning = "00:00 - 03:00";
            this.noon = "11:00 - 14:30";
            this.afternoon = "15:00 - 18:30";
            this.night = "19:00 - 23:30";
        }

        let today = new Date();
        let day = today.getDate();
        let month = today.getMonth() + 1;
        let year = today.getFullYear();
        this.date = year + "/" + (month < 10 ? "0" + month : month) + "/" + (day < 10 ? "0" + day : day);
    }
    componentDidMount() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        this.clearLongTimePeriodChoose();
    }

    union_time(a, b) {
        let result = b.filter(x => {
            return a.some(y => {
                if (x.time == y.time && x.select == true && y.select == true && x.room.length > 0 && y.room.length > 0) {

                    return true
                }

                else {
                    // this.setState({
                    //     reason: [...this.state.reason, x.time + condition_reason(x.shower, x.person)]
                    // })
                    return false
                }
            })
        })
        return result
    }

    doExchange(arr) {
        var len = arr.length;

        if (len >= 2) {
            var len1 = arr[0].length;
            var len2 = arr[1].length;
            var lenBoth = len1 * len2;
            var items = new Array(lenBoth);
            var index = 0;
            for (var i = 0; i < len1; i++) {
                for (var j = 0; j < len2; j++) {
                    items[index] = arr[0][i] + "," + arr[1][j];
                    index++;
                }
            }
            var newArr = new Array(len - 1);
            for (var i = 2; i < arr.length; i++) {
                newArr[i - 1] = arr[i];
            }
            newArr[0] = items;
            return this.doExchange(newArr);
        } else {
            return arr[0];
        }
    }

    arrRepeat(arr) {
        let obj = {};
        for (var i in arr) {
            //存在重複值 
            if (obj[arr[i]])
                return true;
            obj[arr[i]] = true;
        } //不重複 
        return false;
        // var arrStr = JSON.stringify(arr), str;
        // for (var i = 0; i < arr.length; i) {
        //     if (arrStr.indexOf(arr[i]) != arrStr.lastIndexOf(arr[i])) {
        //         return true;
        //     }
        // };
        // return false;
    }

    findRoomPermutaion(array_list) {
        let arranged = this.doExchange(array_list)
        let result = []
        console.log("可執行arranged：", arranged)
        for (let i = 0; i < arranged.length; i++) {
            // console.log("組合：", arranged[i])
            let data = arranged[i].split(',')

            if (!this.arrRepeat(data)) {
                result.push(data)
                console.log("組合可執行：", data)
            }
            else {
                console.log("此組合無法：", data)
            }

        }
        return result
    }

    getTimePeriods(startTime, endTime) {
        const { t } = this.props
        const that = this,
            reservation = this.props.reservation,
            date = this.date,
            csrf_token = document.querySelector('input[name="_token"]').value;
        let { t, package_reservation } = this.props
        this.props.toggleLoading();
        let pacakge_time_room_promise = []
        for (let package_no = 0; package_no < package_reservation.length; package_no++) {
            let current_package = package_reservation[package_no]
            pacakge_time_room_promise.push(axios({
                method: "get",
                url: "../api/time_list",
                params: {
                    shop_id: reservation.shop,
                    service_id: current_package.service,
                    date: date,
                    person: current_package.guestNum,
                    service_provider_id: current_package.operator.join(),
                    // room_id: reservation.roomId,
                    // shower: reservation.shower,
                    start_time: startTime,
                    end_time: endTime
                },
                headers: { 'X-CSRF-TOKEN': csrf_token },
                responseType: 'json'
            }))
        }
        console.log("package_reservation for data:", package_reservation)
        console.log("package_reservation for data2:", )

        axios.all(pacakge_time_room_promise).then(response => {
            console.log("Promise all response:", response)
            if (that.props.loading) that.props.toggleLoading();
            // if (response.length == 1) {
            //     that.props.setSourceData({ timeList: response[0].data });
            // }
            // else {
            response = response.map((res) => {
                return res.data
            })
            // let cross_set = response[0].data
            let qualify_time = {}
            for (let i = 0; i < response.length; i++) {
                for (let time = 0; time < response[i].length; time++) {
                    console.log("回覆：", response[i])
                    let split_time = response[i][time].time.split('\n')[0]
                    if (qualify_time[split_time] == undefined)
                        qualify_time[split_time] = { select: true, select_list: [], rooms: [] }
                    let room_list = []
                    if (response[i][time].room)
                        for (let r = 0; r < response[i][time].room.length; r++) {
                            if (package_reservation[i].shower <= response[i][time].room[r].shower && package_reservation[i].guestNum <= response[i][time].room[r].person) {
                                room_list.push(response[i][time].room[r].id)
                            }
                        }
                    let can_select = true
                    if (room_list == [])
                        can_select = false
                    qualify_time[split_time].select_list.push(can_select)
                    qualify_time[split_time].rooms.push(room_list)
                }

            }
            let disable_time_comment = {}
            let permutation = Object.keys(qualify_time).map((key) => {
                let data = qualify_time[key]
                console.log("收斂組合：", key, ":", data)
                let result = that.findRoomPermutaion(data.rooms)
                if (result.length > 0) {
                    return result[0]
                }
                else {
                    for (let i = 0; i < data.rooms.length; i++) {
                        console.log("!data.rooms[i]:", data.rooms[i])
                        if (data.rooms[i] == false) {
                            disable_time_comment[key] = "\n" + t("OutOf") + t(package_reservation[i].guestNum + "room")
                            console.log("disable_time_comment:", disable_time_comment[key])
                            break
                        }

                    }

                    return false
                }
            })

            console.log("可用組合：", permutation)
            let time_list = response[0]
            for (let i = 0; i < permutation.length; i++) {
                console.log("permutation:", permutation[i])
                let split_time = time_list[i].time.split('\n')[0]
                if (disable_time_comment[split_time])
                    time_list[i].time += disable_time_comment[split_time]
                time_list[i].select = permutation[i] ? true : false //== [] ? false : true
            }
            that.setState({
                time_can_select: permutation,
                longTimePeriodChoose: true
            })
            console.log("time_list:", time_list)
            that.props.setSourceData({ timeList: time_list })

            // for (let i = 0; i < Object.keys(qualify_time))
            //     findRoomPermutaion()



            // for (let i = 1; i < response.length; i++) {
            //     if (response[i].data.length == 0)
            //         that.setState({ hint: "calendarError_noTimelist" })
            //     cross_set = this.union_time(cross_set, response[i].data)
            // }
            // if (cross_set) {
            //     console.log('ok time:', cross_set)
            //     that.props.setSourceData({ timeList: cross_set });
            //     that.setState({
            //         longTimePeriodChoose: true
            //     });
            // }

            // }
        })


        // axios({
        //     method: "get",
        //     url: "../api/time_list",
        //     params: {
        //         shop_id: reservation.shop,
        //         service_id: reservation.service,
        //         date: date,
        //         person: reservation.guestNum,
        //         service_provider_id: reservation.operator.join(),
        //         // room_id: reservation.roomId,
        //         // shower: reservation.shower,
        //         start_time: startTime,
        //         end_time: endTime
        //     },
        //     headers: { 'X-CSRF-TOKEN': csrf_token },
        //     responseType: 'json'
        // })
        //     .then(function (response) {
        //         if (response.statusText == "OK") {
        //             that.props.setSourceData({ timeList: response.data });
        //             if (that.props.loading) that.props.toggleLoading();
        //             if (response.data.length === 0) that.setState({ hint: "calendarError_noTimelist" });
        //             that.setState(function (state) {
        //                 return { longTimePeriodChoose: true };
        //             });
        //         }
        //     })
        //     .catch(function (error) {
        //         console.log(error);
        //         that.props.toggleLoading();
        //         that.setState({ hint: "errorHint_system" });
        //     });
    }
    setTime(event) {
        const time = event.target.innerHTML;
        this.props.setReservation({ time });
    }
    setDate(year, month, day) {
        let date = year + "/" + (month < 10 ? "0" + month : month) + "/" + (day < 10 ? "0" + day : day);
        this.date = date;
        this.clearLongTimePeriodChoose();
        this.props.setReservation({ date });
    }
    setLongTimePeriod(option) {
        var timeInterval = option.split(" - ");
        this.getTimePeriods(timeInterval[0], timeInterval[1]);
    }
    clearLongTimePeriodChoose() {
        this.setState(function (state) {
            return { longTimePeriodChoose: false };
        });
    }
    clearDateAndTimeAndTimeList() {
        this.clearLongTimePeriodChoose();
        this.props.setReservation({
            date: null,
            time: null
        }, () => {
            this.props.setSourceData({ timeList: null });
        });
    }

    render() {
        console.log("sourceData:", this.props.sourceData)

        // console.log("Reasons: ", this.state.reason)
        // if(!this.props.reservation.roomId) location.href = '../reservation/0';
        const reservation = this.props.reservation,
            disabled = (!reservation.date || !reservation.time) || this.props.loading,
            { t } = this.props;
        return (
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
                                {this.props.sourceData.timeList ? this.props.sourceData.timeList.map((time, index) => {
                                    if (time.time == this.props.reservation.time)
                                        return (
                                            <span className="timePeriod selectedTime" key={index} data-index={index}>{time.time}</span>
                                        );
                                    return (
                                        <span className={time.select ? "timePeriod available" : "timePeriod"}
                                            key={index} data-index={index} onClick={time.select ? this.setTime : null}>
                                            {time.time}
                                        </span>);
                                }) : <p>{t(this.state.hint)}</p>}
                                <p className="hint">{t("timeHint")}</p>
                            </div>
                        )}
                </Col>
                <Button currentStep={2} clickHandle={this.props.send} disabled={disabled} />
            </div>
        );
    }
}

module.exports = translate()(CheckTime);