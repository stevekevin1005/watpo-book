import React, { Component } from 'react'
import { translate } from 'react-i18next';
// import { stat } from 'fs';


const Col = ReactBootstrap.Col,
    Row = ReactBootstrap.Row,
    FormGroup = ReactBootstrap.FormGroup,
    FormControl = ReactBootstrap.FormControl,
    ControlLabel = ReactBootstrap.ControlLabel,
    Button = ReactBootstrap.Button;


class Package extends Component {
    constructor(props) {
        super(props);
        this.state = {
            max_people_per_room: this.props.rest_customer
        }
        // this.request_data()
        this.setRoomId = this.setRoomId.bind(this);
        this.updateServiceProviderAndRoomList = this.updateServiceProviderAndRoomList.bind(this);
        this.setMaxGuestNum = this.setMaxGuestNum.bind(this);
        this.setOperator = this.setOperator.bind(this);
        this.setShower = this.setShower.bind(this);


    }


    updateServiceProviderAndRoomList() {
        let { t, rest_customer, setCustomer, sourceData, setReservation, package_reservation, package_no, setPackageService } = this.props
        let current_package = package_reservation[package_no]
        const that = this,
            csrf_token = document.querySelector('input[name="_token"]').value;

        // this.props.toggleLoading();
        axios({
            method: "get",
            url: "../api/service_provider_and_room_list",
            params: {
                service_id: package_reservation[package_no].service,
                shop_id: this.props.reservation.shop
            },
            headers: { 'X-CSRF-TOKEN': csrf_token },
            responseType: 'json'
        })
            .then(function (response) {
                if (that.props.loading)
                    that.props.toggleLoading()
                if (response.statusText == "OK") {
                    let operator = [], operator_text = []

                    for (let i = 0; i < current_package.guestNum; i++) {
                        operator.push(0);
                        operator_text.push(t('NotSpecify'))
                    }
                    that.props.setPackageReservation(package_no, {
                        // guestNum: 1,
                        operator: operator,
                        operator_text: operator_text,
                        service_provider_list: response.data.service_provider_list,
                        room_list: response.data.room
                    }, () => {
                        that.setMaxGuestNum(that.setRoomId);
                    });
                }
            })
            .catch(function (error) {
                console.log(error);
                that.props.showErrorPopUp();
                if (that.props.loading) that.props.toggleLoading();
            });

    }

    componentDidMount() {

        // if (this.props.sourceData.room) {
        //     // 已輸入過此階段的資料，設定可選擇人數的最大值
        //     this.setMaxGuestNum();
        // }
        // else {
        this.updateServiceProviderAndRoomList()
        // }

    }

    setMaxGuestNum(fn) {
        // max guest number is set in initializing, and whenever shower option changes, and is decided by service type
        let { package_no, package_reservation } = this.props
        const showerType = package_reservation[package_no].shower
        // this.props.sourceData.services.find((service, i) => { return service.id == this.props.reservation.service }).shower,
        const rooms = package_reservation[package_no].room_list;

        let max = 0;

        for (let i = 0; i < rooms.length; i++) {
            if (rooms[i].person > max) max = rooms[i].person;
        }

        // 確認目前可提供服務的師傅數量
        const operatorNum = package_reservation[package_no].service_provider_list.length;
        if (operatorNum < max) max = operatorNum;
        if (this.state.max_people_per_room > max)
            this.setState({ max_people_per_room: max });
    }
    setRoomId() {
        // roomId is set in initializing and whenever shower option or guest number is set
        // let { package_no, package_reservation } = this.props
        // const rooms = package_reservation[package_no].room_list,
        //     guestNum = package_reservation[package_no].guestNum;

        // let roomId;

        // // 尋找人數剛好符合的房間
        // for (let i = 0; i < rooms.length; i++) {
        //     if (rooms[i].shower == this.state.shower && rooms[i].person == guestNum) {
        //         roomId = rooms[i].id;
        //         break;
        //     }
        // }
        // // 假如沒有人數剛好符合的房間，找人數較多的
        // if (roomId === undefined) {
        //     for (let i = 0; i < rooms.length; i++) {
        //         if (rooms[i].shower == this.state.shower && rooms[i].person > guestNum) {
        //             roomId = rooms[i].id;
        //             break;
        //         }
        //     }
        // }

        // this.props.setReservation({ roomId });
    }
    setOperator(event) {
        const { t } = this.props
        const value = event.target.options[event.target.selectedIndex].value, // id
            text = event.target.options[event.target.selectedIndex].text,
            index = event.target.dataset.index; // index to save at
        console.log('Selected operator:', text, ' : ', value)
        let { package_no, package_reservation } = this.props
        let current_package = package_reservation[package_no]
        let operator = JSON.parse(JSON.stringify(current_package.operator));
        let operator_text = JSON.parse(JSON.stringify(current_package.operator_text));
        operator[index] = value;
        operator_text[index] = (value != 0) ? text + "號" : t("NotSpecify");
        this.props.setPackageReservation(package_no, { operator, operator_text });

    }

    setShower(event) {
        const el = event.target.options[event.target.selectedIndex],
            shower = (el.value == "true"),
            that = this;
        let { package_no, package_reservation } = this.props
        let current_package = package_reservation[package_no]

        // get and set max guest num 
        this.setState({ shower }, () => {
            that.props.setPackageReservation(package_no, { shower })//, () => {
            //     that.setMaxGuestNum(that.setRoomId);
            // });
        });
    }

    render() {
        let { t, rest_customer, setCustomer, sourceData, setReservation, package_reservation, package_no, setPackageService, disable } = this.props
        let current_package = package_reservation[package_no]
        console.log('package no.:', package_no)
        console.log('package info:', current_package)
        let { max_people_per_room } = this.state
        let selectionList = [], room_size = [], operator_list = []
        if (max_people_per_room) {
            let boundary = max_people_per_room < rest_customer ? max_people_per_room : rest_customer
            for (let i = 1; i <= max_people_per_room; i++) {
                room_size.push(<option key={i} value={i}>{i}</option>)
            }
        }
        // else if(current_package.guestNum){
        //     room_size.push(<option>{t("please_select")}</option>)
        // }
        else {
            room_size.push(<option>{t("please_select")}</option>)
        }

        const selectedOperators = current_package.operator;
        if (current_package.guestNum > 0 && current_package.service_provider_list) {
            for (let i = 0; i < current_package.guestNum; i++) {
                operator_list.push(
                    <FormControl bsClass="form-control operatorOption" componentClass="select" id={"operator" + i} data-index={i} onChange={this.setOperator} defaultValue={current_package.operator[i] ? current_package.operator[i] : null} key={i} onChange={this.setOperator} disabled={disable}>
                        <option key={-1} value={0}>{"不指定"}</option>
                        {current_package.service_provider_list.map((operator, index) => {
                            for (let j = 0; j < selectedOperators.length; j++) {
                                if (j === i) continue; // 當前的跟不指定不用確認
                                if (operator.id == selectedOperators[j]) {
                                    return null;
                                }
                            }
                            return (<option key={index} value={operator.id}>{operator.name}</option>);
                        })}
                    </FormControl>)
            }
        }



        return (
            <div>
                <Row>
                    <Col md={2}>
                        <ControlLabel>{t("service")}</ControlLabel>
                        <FormControl componentClass="select" id="service" defaultValue={package_reservation[package_no].service} placeholder="..." onChange={(e) => setPackageService(package_no, e, this.updateServiceProviderAndRoomList)} disabled={disable}>
                            {/* <option value={1}>{"massage"}</option> */}
                            {sourceData.services && sourceData.services.map((service, index) => {
                                return (<option key={index} value={service.id}>{service.title}</option>);
                            })}
                        </FormControl>
                        {(sourceData.services && package_reservation[package_no].service) && this.props.sourceData.services.find((service, i) => { return service.id == package_reservation[package_no].service }).shower === 1 &&
                            <div style={{ marginBottom: "5px" }}>
                                <ControlLabel>{t("showerOrNot")}</ControlLabel>
                                <FormControl componentClass="select" placeholder="select" defaultValue={this.state.shower} onChange={this.setShower}
                                    inputRef={ref => { this.showerOrNot = ref; }} disabled={disable}>
                                    <option value={true}>{t("yes")}</option>
                                    <option value={false}>{t("no")}</option>
                                </FormControl>
                            </div>
                        }
                        {/* </Col> */}

                        {/* <Col md={2}> */}
                        <ControlLabel>{t("guestNum")}</ControlLabel>
                        <FormControl componentClass="select" placeholder="select" onChange={(e) => setCustomer(package_no, e)} disabled={disable}>
                            {room_size}
                        </FormControl>

                        {/* </Col> */}

                        {/* <Col md={2}> */}

                        <div>
                            <ControlLabel>{t("operator")}</ControlLabel>
                            {operator_list}
                        </div>
                        {/* {reservation.roomId ? <div>
                            <ControlLabel>{t("operator")}</ControlLabel>
                            {operator_list}
                        </div> : sourceData.room ? <p className="hint">{t("errorHint_noRoom")}</p> : null} */}

                        {/* <ControlLabel>{t("operator")}</ControlLabel>
                        <FormControl componentClass="select" placeholder="select" >
                            <option value={0}>{"不指定"}</option>
                            <option value={1}>{1}</option>
                            <option value={2}>{2}</option>
                        </FormControl> */}

                        {/* </Col> */}
                        {/* <Col md={2}> */}
                        {/* </Col> */}
                        {/* <Col md={1}> */}
                        <div className="divider"></div>
                    </Col>
                </Row>
            </div >
        )
    }
}


export default translate()(Package)
