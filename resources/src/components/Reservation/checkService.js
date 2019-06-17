import { translate } from 'react-i18next';
import Button from "./Button";

const Col = ReactBootstrap.Col,
    FormGroup = ReactBootstrap.FormGroup,
    FormControl = ReactBootstrap.FormControl,
    ControlLabel = ReactBootstrap.ControlLabel;

class CheckService extends React.Component {
    constructor(props) {
        super(props);
        this.setReservation = this.setReservation.bind(this);
    }
    componentDidMount() {
        if (!this.props.sourceData.services || !this.props.sourceData.shops) {
            const that = this,
                csrf_token = document.querySelector('input[name="_token"]').value;
            let finished = 0;

            this.props.toggleLoading();

            axios({
                method: "get",
                url: "../api/shop_list",
                headers: { 'X-CSRF-TOKEN': csrf_token },
                responseType: 'json'
            })
                .then(function (response) {
                    if (response.statusText == "OK") {
                        that.props.setSourceData({ shops: response.data });
                        finished += 1;
                        if (finished == 2) {
                            if (that.props.loading) that.props.toggleLoading();
                            that.props.setReservation({ shop: 1 });
                        }
                    }
                })
                .catch(function (error) {
                    console.log(error);
                    if (finished == 2) that.props.toggleLoading();
                    that.props.showErrorPopUp();
                });

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
                            that.props.setReservation({ shop: 1 });
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
    setReservation(event) {
        const el = event.target,
            group = el.id,
            index = +el.options[el.selectedIndex].value;

        let data = {};
        data[group] = index;
        if (index === 5) data['shower'] = true;
        else data['shower'] = false;
        this.props.setReservation(data);
    }
    render() {
        const { t } = this.props,
            reservation = this.props.reservation,
            sourceData = this.props.sourceData,
            disabled = (!reservation.shop) || this.props.loading;

        return (
            <div style={{ paddingTop: "5px" }}>
                <Col md={7}>
                    <FormGroup>
                        <ControlLabel bsClass="control-label branch">{t("branch")}</ControlLabel>
                        <FormControl componentClass="select" id="shop" placeholder="..." defaultValue={reservation.shop} onChange={this.setReservation}>
                            {sourceData.shops && sourceData.shops.map((shop, index) => {
                                return (<option key={index} value={shop.id}>{shop.name}</option>);
                            })}
                        </FormControl>
                    </FormGroup>
                    {/* <FormGroup>
                            <ControlLabel>{t("service")}</ControlLabel>
                            <FormControl componentClass="select" id="service" defaultValue={reservation.service} placeholder="..." onChange={this.setReservation}>
                                {sourceData.services && sourceData.services.map((service,index)=>{
                                    return (<option key={index} value={service.id}>{service.title}</option>);
                                })}
                            </FormControl>
                        </FormGroup> */}
                </Col>
                <Button currentStep={0} clickHandle={this.props.nextStep} disabled={disabled} />
            </div>
        );
    }
}

module.exports = translate()(CheckService);