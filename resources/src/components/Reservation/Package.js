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
        this.state = { services: [] }
        // this.request_data()
    }
    componentDidMount() {
        let current_component = this
        axios({
            method: "get",
            url: "../api/service_list",
            responseType: 'json',
        })
            .then(function (response) {
                if (response.statusText == "OK") {
                    current_component.setState({ services: response.data });
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    }
    render() {
        let { t } = this.props
        let { services } = this.state
        return (
            <div>
                <Row>
                    <Col md={3}>
                        <ControlLabel>{t("service")}</ControlLabel>
                        <FormControl componentClass="select" id="service" >
                            {/* <option value={1}>{"massage"}</option> */}
                            {services && services.map((service, index) => {
                                return (<option key={index} value={service.id}>{service.title}</option>);
                            })}
                        </FormControl>
                    </Col>
                    {/* <Col md={3}>
                    <ControlLabel>{t("showerOrNot")}</ControlLabel>
                    <FormControl componentClass="select" placeholder="select" //defaultValue={this.state.shower} onChange={this.setShower}
                    >
                        <option value={true}>{t("yes")}</option>
                        <option value={false}>{t("no")}</option>
                    </FormControl>
                </Col> */}

                    <Col md={3}>
                        <ControlLabel>{t("guestNum")}</ControlLabel>
                        <FormControl componentClass="select" placeholder="select" >
                            <option value={1}>{1}</option>
                            <option value={2}>{2}</option>
                        </FormControl>

                    </Col>

                    <Col md={2}>

                        <ControlLabel>{t("operator")}</ControlLabel>
                        <FormControl componentClass="select" placeholder="select" >
                            <option value={0}>{"不指定"}</option>
                            <option value={1}>{1}</option>
                            <option value={2}>{2}</option>
                        </FormControl>

                    </Col>
                    <Col md={1}>
                        <div className="divider"></div>
                    </Col>
                </Row>
            </div >
        )
    }
}


export default translate()(Package)
