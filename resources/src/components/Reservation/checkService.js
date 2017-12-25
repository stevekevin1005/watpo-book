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
    componentDidMount(){
        this.props.setReservation("shop", 0);
        this.props.setReservation("service", 0);
        // get data of shops and services
        const sourceData = this.props.sourceData;
        // check if data is already loaded
        if(sourceData.shops && sourceData.services) return;

        // if not, fetch data of shops and services
        const that = this,
              csrf_token = document.querySelector('input[name="_token"]').value;
        let finished = 0;
        this.props.toggleLoading(true);
        axios({
                method: "get",
                url: "../api/shop_list",
                headers: {'X-CSRF-TOKEN': csrf_token},
                responseType: 'json'
            })
            .then(function (response) {
                if(response.statusText == "OK"){
                    that.props.setSourceData("shops", response.data);

                    that.props.toggleLoading(false);
                    finished += 1;
                    if(finished == 2) that.props.toggleLoading(false);
                }
            })
            .catch(function (error) {
                console.log(error);
                that.props.toggleLoading(false);
                finished += 1;
                if(finished == 2) that.props.toggleLoading(false);
            });
        axios({
                method: "get",
                url: "../api/service_list",
                responseType: 'json',
                headers: {'X-CSRF-TOKEN': csrf_token}
            })
            .then(function (response) {
                if(response.statusText == "OK"){
                    that.props.setSourceData("services", response.data);
                    that.props.toggleLoading(false);
                    finished += 1;
                    if(finished == 2) that.props.toggleLoading(false);
                }
            })
            .catch(function (error) {
                console.log(error);
                finished += 1;
                if(finished == 2) that.props.toggleLoading(false);
            });
    }
    setReservation(event){
        const el = event.target,
              group = el.id,
              index = parseInt(el.options[el.selectedIndex].value);
        this.props.setReservation(group, index);
    }
    render(){
        const { t } = this.props, sourceData = this.props.sourceData,
              reservation = this.props.reservation,
              disabled = (reservation.shop === undefined || reservation.service === undefined) || this.props.loading;

        return(
            <Grid>
                <Row>
                    <Col md={7}>
                        <FormGroup controlId="formControlsSelect">
                            <ControlLabel bsClass="control-label branch">{t("branch")}</ControlLabel>
                            <FormControl componentClass="select" id="shop" placeholder="..." defaultValue={this.props.reservation.shop === undefined?this.props.reservation.shop:null} onChange={this.setReservation}>
                                {sourceData.shops && sourceData.shops.map((shop,index)=>{
                                    return (<option key={index} value={index}>{shop.name}</option>);
                                })}
                            </FormControl>
                        </FormGroup>
                        <FormGroup controlId="formControlsSelect">
                            <ControlLabel>{t("service")}</ControlLabel>
                            <FormControl componentClass="select" id="service" defaultValue={this.props.reservation.service === undefined?this.props.reservation.service:null} placeholder="..." onChange={this.setReservation}>
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

const mapStateToProps = (state)=>{
    return {
        reservation: state.reservation,
        sourceData: state.sourceData,
        loading: state.loading
    }
}

const mapDispatchToProps = (dispatch)=>{
    return bindActionCreators({
        toggleLoading: toggleLoading,
        setReservation: setReservation,
        setSourceData: setSourceData
    },dispatch);
}

CheckService = connect(mapStateToProps,mapDispatchToProps)(CheckService);

module.exports = translate()(CheckService);