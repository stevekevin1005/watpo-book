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
            group = "shop",
            index = +el.value;

        let data = {};
        data[group] = index;
        if (index === 5) data['shower'] = true;
        else data['shower'] = false;
        this.props.setReservation(data);
        this.props.nextStep();
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
                        {sourceData.shops && sourceData.shops.map((shop, index) => {
                            return (<FormControl componentClass="button" currentStep={0} value={shop.id} onClick={this.setReservation}>{shop.name}</FormControl>);
                        })}
                        
                    </FormGroup>
                </Col>
            </div>
        );
    }
}

module.exports = translate()(CheckService);