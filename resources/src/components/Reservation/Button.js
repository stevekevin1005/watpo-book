import { translate } from 'react-i18next';

const Col = ReactBootstrap.Col,
      Button = ReactBootstrap.Button;

const Button_ = (props)=>{
    const { t } = props;
    return (
        <Col md={12}>
            <Button bsStyle="primary" bsSize="large" disabled={props.disabled} onClick={props.disabled?null:props.clickHandle}>
                {props.currentStep==2?t("send"):t("nextStep")}
            </Button>
        </Col>
    );
}

module.exports = translate()(Button_);