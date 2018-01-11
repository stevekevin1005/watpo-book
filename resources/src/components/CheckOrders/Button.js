import { translate } from 'react-i18next';

const Col = ReactBootstrap.Col,
      Button = ReactBootstrap.Button;

const Button_ = (props)=>{
    const { t } = props;
    return (
        <Col md={12}>
            <Button bsStyle="primary" bsSize="large" type="submit"
                    disabled={props.disabled} onClick={props.disabled?null:props.clickHandle}>
                {t("nextStep")}
            </Button>
        </Col>
    );
}

module.exports = translate()(Button_);