import React, { Component } from 'react'
import { translate } from 'react-i18next';
import NextButton from "./Button";
import Package from './Package'


const Col = ReactBootstrap.Col,
    Row = ReactBootstrap.Row,
    FormGroup = ReactBootstrap.FormGroup,
    FormControl = ReactBootstrap.FormControl,
    ControlLabel = ReactBootstrap.ControlLabel,
    Button = ReactBootstrap.Button;


class CheckPackage extends Component {
    constructor(props) {
        super(props)
        this.state = {
            current_package: 0,
            package_stack: [],
            rest_customer: this.props.reservation.total_guest_num,
            is_arranged_customer: []
        }
        this.setCustomer = this.setCustomer.bind(this);
        this.setPackageService = this.setPackageService.bind(this)
        this.add_packages = this.add_packages.bind(this)
        this.remove_packages = this.remove_packages.bind(this)
        this.updateRestPeople = this.updateRestPeople.bind(this)
        this.nextStep = this.nextStep.bind(this);


    }
    componentDidMount() {
        if (this.props.loading)
            this.props.toggleLoading()
        this.props.claerPackage()
        const that = this,
            csrf_token = document.querySelector('input[name="_token"]').value;

        axios({
            method: "get",
            url: "../api/service_provider_and_room_list",
            params: {
                service_id: 1,
                shop_id: this.props.reservation.shop
            },
            headers: { 'X-CSRF-TOKEN': csrf_token },
            responseType: 'json'
        })
            .then(function (response) {
                if (that.props.loading)
                    that.props.toggleLoading()
                if (response.statusText == "OK") {
                    // let operator = [], operator_text = []

                    // for (let i = 0; i < current_package.guestNum; i++) {
                    //     operator.push(0);
                    //     operator_text.push(t('NotSpecify'))
                    // }
                    that.props.setReservation({
                        service_provider_list: response.data.service_provider_list
                    })
                    // that.props.setPackageReservation(package_no, {
                    //     // guestNum: 1,
                    //     operator: operator,
                    //     operator_text: operator_text,
                    //     // service_provider_list: response.data.service_provider_list,
                    //     room_list: response.data.room
                    // }, () => {
                    //     that.setMaxGuestNum(that.setRoomId);
                    // });
                }
            })
            .catch(function (error) {
                console.log(error);
                that.props.showErrorPopUp();
                if (that.props.loading) that.props.toggleLoading();
            });


    }

    setCustomer(no, e) {
        let amount = parseInt(e.target.options[event.target.selectedIndex].value),
            customer_list = this.state.is_arranged_customer,
            operator = [], operator_text = [], service = []


        for (let i = 0; i < amount; i++) {
            service.push(1)
            operator.push(0);
            operator_text.push('不指定')
        }
        let that = this;
        this.props.setPackageReservation(no, { service: service, guestNum: amount, operator: operator, operator_text: operator_text });

    }

    setPackageService(no, event, callback) {
        const el = event.target,
            group = el.id,
            index = +el.options[el.selectedIndex].value;

        let data = {};
        data[group] = index;
        let { sourceData } = this.props
        data['shower'] = sourceData.services.find(s => { return s.id == index }).shower === 1

        this.props.setPackageReservation(no, data, callback);
    }


    updateRestPeople() {

    }


    remove_packages(no) {

        let { package_reservation } = this.props
        let r = package_reservation[no].guestNum + this.state.rest_customer
        this.props.removePackage(no)
        this.setState({ rest_customer: r })

    }

    add_packages() {

        this.props.appendNewPackage();

    }
    nextStep(event) {
        event.preventDefault();

        this.props.nextStep();
    }
    render() {
        const { t, package_reservation, reservation } = this.props

        let package_stack = []
        let total_customer = reservation.total_guest_num;
        let is_arranged_customer = 0
        for (let i = 0; i < package_reservation.length; i++) {
            is_arranged_customer += parseInt(package_reservation[i].guestNum)
        }
        let rest_customer = total_customer - is_arranged_customer
        if (rest_customer != reservation.unarranged_people)
            this.props.setReservation({ unarranged_people: rest_customer })
        for (let i = 0; i < package_reservation.length; i++) {
            package_stack.push((
                <div>
                    {i == (package_reservation.length - 1) && (<div>
                        <div className="col-md-11">
                        </div>
                        <button style={{ backgroundColor: "rgba(0,0,0,0)", border: 'none', fontSize: '25px' }} type="button" className="" aria-label="Close" onClick={() => {
                            this.remove_packages(i)
                        }}>
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>)}
                    <Package
                        disable={i !== (package_reservation.length - 1)}
                        package_no={i}
                        setPackageService={this.setPackageService}
                        {...this.props}
                        setCustomer={this.setCustomer}
                        rest_customer={reservation.unarranged_people == 0 ? package_reservation[i].guestNum : reservation.unarranged_people}
                    />
                </div>)
            )
        }
        return (
            <div>
                <FormGroup>
                    {package_stack}

                    <Col md={12} style={{ "justify-content": "center", "display": "flex" }}>
                        {reservation.unarranged_people > 0 && <Button bsStyle="primary" bsSize="large"
                            onClick={(e) => {
                                console.log("Add new package")
                                this.add_packages()
                            }}>
                            {t("AddPackage")}
                        </Button>}
                    </Col>
                </FormGroup>
                <NextButton currentStep={2} clickHandle={this.nextStep} disabled={reservation.unarranged_people !== 0} />
            </div>
        )
    }
}
export default translate()(CheckPackage)