// 負責寫資料(分店,服務)到global state
import { translate } from 'react-i18next';
import {connect} from "react-redux";

const Button = ReactBootstrap.Button,
        Grid = ReactBootstrap.Grid,
        Row = ReactBootstrap.Row,
        Col = ReactBootstrap.Col,
        FormGroup = ReactBootstrap.FormGroup,
        FormControl = ReactBootstrap.FormControl,
        ControlLabel = ReactBootstrap.ControlLabel;

class CheckService extends React.Component{
    constructor(props){
        super(props);
    }
    render(){
        const { t } = this.props;

        return(
            <Grid>
                <Row>
                    <Col md={7}>
                        <FormGroup controlId="formControlsSelect">
                            <ControlLabel bsClass="control-label branch">{t("branch")}</ControlLabel>
                            <FormControl componentClass="select" placeholder="select">
                                <option value="select">select</option>
                                <option value="other">...</option>
                            </FormControl>
                        </FormGroup>
                        <FormGroup controlId="formControlsSelect">
                            <ControlLabel>{t("service")}</ControlLabel>
                            <FormControl componentClass="select" placeholder="select">
                                <option value="select">select</option>
                                <option value="other">...</option>
                            </FormControl>
                        </FormGroup>
                    </Col>
                </Row>
            </Grid>
        );
    }
}


module.exports = translate()(CheckService);