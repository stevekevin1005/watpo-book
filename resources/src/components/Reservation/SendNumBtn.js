import { translate } from 'react-i18next';
import React, { Component } from 'react'
import { Modal } from 'react-bootstrap';
import { connect } from "react-redux"
import { sendSMS, checkSMS, SwitchCodeModal } from '../../actions'




class SendNumBtn extends Component {
    constructor(props) {
        super(props)
        this.state = {
            errorMsg: '',
            code: '',
            show: false
        }
        this.handleShow = this.handleShow.bind(this);
        this.handleClose = this.handleClose.bind(this);
        this.setCode = this.setCode.bind(this);

    }

    handleClose() {
        this.setState({ show: false });
    }

    handleShow() {
        this.setState({ show: true });
    }

    setCode(e) {
        let code = e.target.value;

        this.setState({ code });
        console.log("code: ", code);
    }

    render() {
        let { checkOrdersInfo } = this.props
        // console.log("this.props: ", this.props);
        const { t } = this.props;
        const FormGroup = ReactBootstrap.FormGroup,
            FormControl = ReactBootstrap.FormControl,
            ControlLabel = ReactBootstrap.ControlLabel;
        const Col = ReactBootstrap.Col, Button = ReactBootstrap.Button;
        return (
            <Col md={12}>
                <span>{this.state.errorMsg}</span>
                <Button bsStyle="primary" bsSize="large" onClick={(e) => {
                    console.log("name: " + this.props.name)
                    this.props.sendSMS(this.props.name, this.props.phone);
                    // this.props.showCodeEnter(true)

                }}>
                    {this.props.reEnter ? "請輸入先前驗證碼" : "寄送驗證碼"}
                </Button>
                {<Modal show={this.props.show} onHide={() => { this.props.showCodeEnter(false) }}>
                    <Modal.Header closeButton>
                        <Modal.Title>{t("SMSCode")}</Modal.Title>
                    </Modal.Header>

                    <Modal.Body>
                        <FormControl
                            type="text"
                            placeholder="請輸入驗證碼..."
                            onChange={this.setCode}
                        />
                    </Modal.Body>

                    <Modal.Footer>
                        <Button onClick={(e) => {
                            this.props.showCodeEnter(false);
                        }}>{t("Close")}</Button>
                        <Button bsStyle="primary" onClick={(e) => {
                            if (this.state.code != "") {
                                this.props.checkSMS(this.props.name, this.props.phone, this.state.code);
                            }
                            else {
                                this.setState({ codeMsg: t("ErrorCode") });

                            }
                        }}>{t("EnterCode")}</Button>
                    </Modal.Footer>
                </Modal>}
            </Col>
        )
    }
}

const mapStateToProps = (state) => {
    console.log("state input: ", state);
    return {
        EnterCode: state.phoneValidator.reEnter,
        show: state.phoneValidator.isopen
    }
}

const mapDispatchToProps = (dispatch) => ({
    showCodeEnter: (isopen) => dispatch(SwitchCodeModal(isopen)),
    sendSMS: (name, number) => dispatch(sendSMS(name, number)),
    checkSMS: (name, number, code) => dispatch(checkSMS(name, number, code))
});

export default connect(mapStateToProps, mapDispatchToProps)(SendNumBtn);

