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
            max_people_per_room: this.props.rest_customer > 3 ? 3 : this.props.rest_customer
        }
        // this.request_data()
        this.setRoomId = this.setRoomId.bind(this);
        this.updateServiceProviderAndRoomList = this.updateServiceProviderAndRoomList.bind(this);
        this.setMaxGuestNum = this.setMaxGuestNum.bind(this);
        this.setOperator = this.setOperator.bind(this);
        this.setShower = this.setShower.bind(this);
        this.setService = this.setService.bind(this);


    }


    updateServiceProviderAndRoomList() {
        let { t, rest_customer, setCustomer, sourceData, setReservation, package_reservation, package_no, setPackageService } = this.props
        let current_package = package_reservation[package_no]
        const that = this,
            csrf_token = document.querySelector('input[name="_token"]').value;
        this.setMaxGuestNum()
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
        let { package_no, package_reservation, reservation } = this.props
        const showerType = package_reservation[package_no].shower
        // this.props.sourceData.services.find((service, i) => { return service.id == this.props.reservation.service }).shower,
        // const rooms = package_reservation[package_no].room_list;

        let max = 3;

        // for (let i = 0; i < rooms.length; i++) {
        //     if (rooms[i].person > max) max = rooms[i].person;
        // }

        // 確認目前可提供服務的師傅數量
        const operatorNum = reservation.service_provider_list.length;
        if (operatorNum < max) max = operatorNum;
        if (this.state.max_people_per_room > max)
            this.setState({ max_people_per_room: max });
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
        operator[index] = parseInt(value);
        operator_text[index] = (value != 0) ? text + "號" : t("NotSpecify");
        this.props.setPackageReservation(package_no, { operator, operator_text });

    }

    setService(event) {
        const value = event.target.options[event.target.selectedIndex].value, // id
            index = event.target.dataset.index; // index to save at
        let { package_no, package_reservation } = this.props
        let current_package = package_reservation[package_no]
        let service_list = JSON.parse(JSON.stringify(current_package.service));
        service_list[index] = parseInt(value);
        this.props.setPackageReservation(package_no, { service: service_list });
        // this.updateServiceProviderAndRoomList()
    }

    setShower(event) {
        const el = event.target.options[event.target.selectedIndex],
            shower = (el.value == "true"),
            that = this;
        let { package_no, package_reservation } = this.props
        let current_package = package_reservation[package_no]
        console.log("Select shower to :", shower)
        that.props.setPackageReservation(package_no, { shower })
        // get and set max guest num 
        // this.setState({ shower }, () => {
        //     that.props.setPackageReservation(package_no, { shower })//, () => {
        //     //     that.setMaxGuestNum(that.setRoomId);
        //     // });
        // });
    }

    setRoomId() {

    }
    
    render() {
        let { t, rest_customer, setCustomer, sourceData, reservation, package_reservation, package_no, setPackageService, disable } = this.props
        let current_package = package_reservation[package_no]
        // console.log('package no.:', package_no)
        // console.log('package info:', current_package)
        // console.log('rest_customer:', rest_customer)
        let { max_people_per_room } = this.state
        let selectionList = [], room_size = [], operator_list = []
        if (max_people_per_room) {
            let boundary = max_people_per_room <= rest_customer ? max_people_per_room : (rest_customer == 1 ? 1 : rest_customer + 1)
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
        // console.log('Reducing operators...', package_reservation)

        let selectedOperators = package_reservation.reduce((accu, cur) => {
            accu.push(...cur.operator)
            return accu
        }, [])
        // console.log("Selected opertaors:", selectedOperators)
        // const selectedOperators = current_package.operator;
        if (current_package.guestNum > 0 && reservation.service_provider_list) {
            for (let i = 0; i < current_package.guestNum; i++) {
                operator_list.push(
                    <div>
                        <ControlLabel>{t("service") + (i + 1)}</ControlLabel>
                        <FormControl componentClass="select" id="service" value={package_reservation[package_no].service[i]} defaultValue={package_reservation[package_no].service[i]} data-index={i} placeholder="..." onChange={(e) => this.setService(e)} disabled={disable}>
                            {/* <option value={1}>{"massage"}</option> */}
                            {sourceData.services && sourceData.services.map((service, index) => {
                                return (<option key={index} value={service.id}>{service.title}</option>);
                            })}
                        </FormControl>
                        <ControlLabel>{t("operator")}</ControlLabel>
                        <FormControl bsClass="form-control operatorOption" componentClass="select" id={"operator" + i} data-index={i} onChange={this.setOperator} defaultValue={current_package.operator[i] ? current_package.operator[i] : null} key={i} onChange={this.setOperator} disabled={disable}>
                            <option key={-1} value={0}>{"不指定"}</option>
                            {reservation.service_provider_list.map((operator, index) => {
                                let serviceCategory = package_reservation[package_no].service[i];
                                if ((serviceCategory == 1 || serviceCategory == 3) && operator.service_1 == 0) return null;
                                if ((serviceCategory == 2 || serviceCategory == 4) && operator.service_2 == 0) {
                                    return null;
                                }
                                if (serviceCategory == 5 && operator.service_3 == 0) return null;
                                for (let j = 0; j < selectedOperators.length; j++) {
                                    if (selectedOperators[j] === current_package.operator[i]) continue; // 當前的跟不指定不用確認
                                    if (operator.id == selectedOperators[j]) {
                                        return null;
                                    }
                                }
                                return (<option key={index} value={operator.id}>{operator.name}</option>);
                            })}
                        </FormControl>
                    </div>)
            }
        }

        let needs_shower_obj = this.props.sourceData.services.filter((service, idx) => {
            return service.shower == 1
        })
        let needs_shower_ids = needs_shower_obj.map(val => val.id)
        let should_show_shower = false;
        should_show_shower = needs_shower_ids.some(r => package_reservation[package_no].service.indexOf(r) >= 0)
                                && package_reservation[package_no].service.indexOf(5) < 0
        return (
            <div>
                <Row>
                    <Col md={12}>
                        <h4>{t('Room') + (package_no + 1) + ":"}</h4>
                    </Col>
                </Row>
                <Row>
                    <Col md={12}>

                        {/* 人數 */}
                        <ControlLabel>{t("guestNum")}</ControlLabel>
                        <FormControl componentClass="select" placeholder="select" onChange={(e) => setCustomer(package_no, e)} disabled={disable}>
                            {room_size}
                        </FormControl>

                        {operator_list}

                        {/* <ControlLabel>{t("service")}</ControlLabel>
                        <FormControl componentClass="select" id="service" defaultValue={package_reservation[package_no].service} placeholder="..." onChange={(e) => setPackageService(package_no, e, this.updateServiceProviderAndRoomList)} disabled={disable}>
                            {sourceData.services && sourceData.services.map((service, index) => {
                                return (<option key={index} value={service.id}>{service.title}</option>);
                            })}
                        </FormControl> */}



                        {/* <div>
                            <ControlLabel>{t("operator")}</ControlLabel>
                            {operator_list}
                        </div> */}

                        {/* 是否衛浴 */}
                        {/* {(sourceData.services && package_reservation[package_no].service.length > 0) && this.props.sourceData.services.find((service, i) => {
                            console.log("service id:", service.id)
                            console.log("reservation service:", package_reservation[package_no].service)
                            let return_data = false
                            for (let j = 0; j < package_reservation[package_no].service.length; j++) {
                                if (service.id == package_reservation[package_no].service[j]) {
                                    return_data = true
                                }
                            }
                            return return_data

                            // if (service.id in package_reservation[package_no].service) {
                            //     return true
                            // }
                            // else
                            //     return false
                            // return service.id == package_reservation[package_no].service[i] 
                        }).shower === 1 && */}
                        {sourceData.services && package_reservation[package_no].service.length > 0 && should_show_shower &&
                            <div style={{ marginBottom: "5px" }}>
                                <ControlLabel>{t("showerOrNot")}</ControlLabel>
                                <FormControl componentClass="select" placeholder="select" defaultValue={package_reservation[package_no].shower} onChange={this.setShower}
                                    inputRef={ref => { this.showerOrNot = ref; }} disabled={disable}>
                                    <option value={true}>{t("yes")}</option>
                                    <option value={false}>{t("no")}</option>
                                </FormControl>
                            </div>
                        }

                        <div className="divider" style={{ width: "85%", height: '1px' }}></div>
                    </Col>
                </Row>
            </div >
        )
    }
}


export default translate()(Package)
