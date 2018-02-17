import { translate } from 'react-i18next';
import Button from "./Button";

const Col = ReactBootstrap.Col,
        FormGroup = ReactBootstrap.FormGroup,
        FormControl = ReactBootstrap.FormControl,
        ControlLabel = ReactBootstrap.ControlLabel;

class CheckService extends React.Component{
    constructor(props){
        super(props);
        
        this.state={
            shop: this.props.reservation.shop || 1,
            service: this.props.reservation.service || 1,

            services: this.props.sourceData.services || null,
            shops: this.props.sourceData.shops || null
        };

        this.setReservation = this.setReservation.bind(this);
        this.save = this.save.bind(this);
    }
    setReservation(event){
        const el = event.target,
              group = el.id,
              index = +el.options[el.selectedIndex].value;

        let data = {};
        data[group] = index;
        this.setState(data);
    }
    save(){
        this.props.saveReservationAndSourceData({
            shop: this.state.shop,
            service: this.state.service
        },{
            shops: this.state.shops,
            services: this.state.services
        },
            this.props.nextStep
        );
    }
    render(){
        const { t } = this.props,
              disabled = ( !this.state.shop || !this.state.service ) || this.props.loading;

        return(
                <div style={{paddingTop: "5px"}}>
                    <Col md={7}>
                        <FormGroup>
                            <ControlLabel bsClass="control-label branch">{t("branch")}</ControlLabel>
                            <FormControl componentClass="select" id="shop" placeholder="..." defaultValue={this.state.shop} onChange={this.setReservation}>
                                {this.state.shops && this.state.shops.map((shop,index)=>{
                                    return (<option key={index} value={shop.id}>{shop.name}</option>);
                                })}
                            </FormControl>
                        </FormGroup>
                        <FormGroup>
                            <ControlLabel>{t("service")}</ControlLabel>
                            <FormControl componentClass="select" id="service" defaultValue={this.state.service} placeholder="..." onChange={this.setReservation}>
                                {this.state.services && this.state.services.map((service,index)=>{
                                    return (<option key={index} value={service.id}>{service.title}</option>);
                                })}
                            </FormControl>
                        </FormGroup>
                    </Col>
                    <Button currentStep={0} clickHandle={this.save} disabled={disabled}/>
                </div>
        );
    }
}

module.exports = translate()(CheckService);