// 負責寫資料(分店,服務)到global state
import { translate } from 'react-i18next';
import {connect} from "react-redux";
import {bindActionCreators} from "redux";
import toggleLoading from "../../dispatchers/toggleLoading";
import setReservation from "../../dispatchers/setReservation";
import setSourceData from "../../dispatchers/setSourceData";
import Button from "./Button";

const Grid = ReactBootstrap.Grid,
        Row = ReactBootstrap.Row,
        Col = ReactBootstrap.Col,
        FormGroup = ReactBootstrap.FormGroup,
        FormControl = ReactBootstrap.FormControl,
        ControlLabel = ReactBootstrap.ControlLabel;

class CheckService extends React.Component{
    constructor(props){
        super(props);
        this.setReservation = this.setReservation.bind(this);
    }
    setReservation(event){
        const el = event.target,
              group = el.id,
              index = +el.options[el.selectedIndex].value;

        this.props.setReservation(group, index);
    }
    render(){
        const { t } = this.props, sourceData = this.props.sourceData,
              reservation = this.props.reservation,
              disabled = ( !(reservation.shop || reservation.shop === 0) || !(reservation.service || reservation.service === 0) ) || this.props.loading;

        return(
            <Grid>
                <Row>
                    <Col md={7}>
                        <FormGroup controlId="formControlsSelect">
                            <ControlLabel bsClass="control-label branch">{t("branch")}</ControlLabel>
                            <FormControl componentClass="select" id="shop" placeholder="..." defaultValue={this.props.reservation.shop} onChange={this.setReservation}>
                                {sourceData.shops && sourceData.shops.map((shop,index)=>{
                                    return (<option key={index} value={index}>{shop.name}</option>);
                                })}
                            </FormControl>
                        </FormGroup>
                        <FormGroup controlId="formControlsSelect">
                            <ControlLabel>{t("service")}</ControlLabel>
                            <FormControl componentClass="select" id="service" defaultValue={this.props.reservation.service} placeholder="..." onChange={this.setReservation}>
                                {sourceData.services && sourceData.services.map((service,index)=>{
                                    return (<option key={index} value={index}>{service.title}</option>);
                                })}
                            </FormControl>
                        </FormGroup>
                    </Col>
                    <Button currentStep={0} clickHandle={this.props.nextStep} disabled={disabled}/>
                </Row>
            </Grid>
        );
    }
}

module.exports = translate()(CheckService);