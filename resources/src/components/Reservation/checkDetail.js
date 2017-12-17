// 負責寫資料(師傅,人數,房號,姓名,電話)到global state
import { translate } from 'react-i18next';
import {connect} from "react-redux";

const Button = ReactBootstrap.Button,
Grid = ReactBootstrap.Grid,
Row = ReactBootstrap.Row,
Col = ReactBootstrap.Col,
FormGroup = ReactBootstrap.FormGroup,
FormControl = ReactBootstrap.FormControl,
ControlLabel = ReactBootstrap.ControlLabel,
HelpBlock = ReactBootstrap.HelpBlock;

class CheckDetail extends React.Component{
    constructor(props){
        super(props);
    }
    render(){
        const { t } = this.props;

        return(
            <Grid>
            <Row className="show-grid">
            <FormGroup controlId="formControlsSelect">
                <Col md={5}>
                        <ControlLabel>{t("operator")}</ControlLabel>
                        <FormControl componentClass="select" placeholder="select">
                            <option value="select">select</option>
                            <option value="other">...</option>
                        </FormControl>
                        <FormControl.Feedback />
                        <HelpBlock></HelpBlock>
                        <ControlLabel>{t("roomNumber")}</ControlLabel>
                        <FormControl componentClass="select" placeholder="select">
                            <option value="select">select</option>
                            <option value="other">...</option>
                        </FormControl>
                        <FormControl.Feedback />
                        <HelpBlock></HelpBlock>
                        <ControlLabel>{t("guestNum")}</ControlLabel>
                        <FormControl componentClass="select" placeholder="select">
                            <option value="select">select</option>
                            <option value="other">...</option>
                        </FormControl>
                        <FormControl.Feedback />
                        <HelpBlock></HelpBlock>
               </Col>
               
               <Col md={1}>
               <div className="divider"></div>
               </Col>

               <Col md={5}>
                    <ControlLabel>{t("reservatorName")}</ControlLabel>
                    <FormControl
                        type="text"
                        placeholder="Enter text"
                    />
                    <FormControl.Feedback />
                    <HelpBlock></HelpBlock>
                    <ControlLabel>{t("contactNumber")}</ControlLabel>
                    <FormControl
                        type="text"
                        placeholder="Enter text"
                    />
                    <FormControl.Feedback />
                    <HelpBlock></HelpBlock>
                </Col>
             </FormGroup>
            </Row>
        </Grid>
        );
    }
}


module.exports = translate()(CheckDetail);