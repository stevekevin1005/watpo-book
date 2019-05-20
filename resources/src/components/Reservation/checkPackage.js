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

// const Package_select = (props) => {
//     return (
//         <div>
//             <Row>
//                 <Col md={3}>
//                     <ControlLabel>{props.t("service")}</ControlLabel>
//                     <FormControl componentClass="select" id="service" >
//                         {sourceData.services && sourceData.services.map((service, index) => {
//                             return (<option key={index} value={service.id}>{service.title}</option>);
//                         })}
//                     </FormControl>
//                 </Col>
//                 {/* <Col md={3}>
//                     <ControlLabel>{props.t("showerOrNot")}</ControlLabel>
//                     <FormControl componentClass="select" placeholder="select" //defaultValue={this.state.shower} onChange={this.setShower}
//                     >
//                         <option value={true}>{props.t("yes")}</option>
//                         <option value={false}>{props.t("no")}</option>
//                     </FormControl>
//                 </Col> */}

//                 <Col md={3}>
//                     <ControlLabel>{props.t("guestNum")}</ControlLabel>
//                     <FormControl componentClass="select" placeholder="select" >
//                         <option value={1}>{1}</option>
//                         <option value={2}>{2}</option>
//                     </FormControl>

//                 </Col>

//                 <Col md={2}>

//                     <ControlLabel>{props.t("operator")}</ControlLabel>
//                     <FormControl componentClass="select" placeholder="select" >
//                         <option value={0}>{"不指定"}</option>
//                         <option value={1}>{1}</option>
//                         <option value={2}>{2}</option>
//                     </FormControl>

//                 </Col>
//                 <Col md={1}>
//                     <div className="divider"></div>
//                 </Col>
//             </Row>
//         </div>
//     )
// }

class CheckPackage extends Component {
    constructor(props) {
        super(props)
        this.state = {
            package_stack: []
        }


    }
    componentDidMount() {
        if (!this.props.sourceData.services || !this.props.sourceData.shops) {
            const that = this,
                csrf_token = document.querySelector('input[name="_token"]').value;
            let finished = 0;

            this.props.toggleLoading();


            axios({
                method: "get",
                url: "../api/service_list",
                responseType: 'json',
                headers: { 'X-CSRF-TOKEN': csrf_token }
            })
                .then(function (response) {
                    if (response.statusText == "OK") {
                        that.props.setSourceData({ services: response.data });
                        finished += 1;
                        if (finished == 2) {
                            if (that.props.loading) that.props.toggleLoading();
                            that.props.setReservation({ shop: 1, service: 1 });
                        }
                    }
                })
                .catch(function (error) {
                    console.log(error);
                    if (finished == 2) that.props.toggleLoading();
                    that.props.showErrorPopUp();
                });
        }
    }

    add_packages() {
        const { t } = this.props
        let temp_stack = this.state.package_stack
        temp_stack.push((<Package />))
        console.log("package amount:", temp_stack.length)
        this.setState({
            package_stack: temp_stack
        })
    }
    nextStep(event) {
        event.preventDefault();


        this.props.nextStep();
    }
    render() {
        const { t } = this.props
        let { package_stack } = this.state;
        return (
            <div>
                <FormGroup>
                    {package_stack}

                    <Col md={12} style={{ "justify-content": "center", "display": "flex" }}>
                        <Button bsStyle="primary" bsSize="large"
                            onClick={(e) => {
                                console.log("Add new package")
                                this.add_packages()
                            }}>
                            {t("AddPackage")}
                        </Button>
                    </Col>
                </FormGroup>
                <NextButton currentStep={1} clickHandle={this.nextStep} />
            </div>
        )
    }
}
export default translate()(CheckPackage)