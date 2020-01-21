// 負責寫資料(日期,時段)到global state
import { translate } from 'react-i18next';
import { connect } from "react-redux";
import { bindActionCreators } from "redux";
import { Modal, Container, Button } from 'react-bootstrap'
import toggleLoading from "../../dispatchers/toggleLoading";
import Alert from '../Reservation/Alert'
import { renderToStaticMarkup } from 'react-dom/server';

import SweetAlert from 'sweetalert-react';


const Grid = ReactBootstrap.Grid,
    Row = ReactBootstrap.Row,
    Col = ReactBootstrap.Col,
    ListGroupItem = ReactBootstrap.ListGroupItem,
    ListGroup = ReactBootstrap.ListGroup,
    Table = ReactBootstrap.Table;


class OrdersInfo extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            hint: "ordersInfoHint",
            orders: [],
            id: null,
            showDetail: false,
            detail_data: ''
        };
        this.cancel = this.cancel.bind(this);
        this.getOrders = this.getOrders.bind(this);
        this.confirmCancel = this.confirmCancel.bind(this);
        this.showDetail = this.showDetail.bind(this);
    }
    componentDidMount() {
        this.getOrders();
    }
    getOrders() {
        const that = this,
            csrf_token = document.querySelector('input[name="_token"]').value;
        that.props.toggleLoading(true);
        // 取得預約資料列表
        axios({
            method: "get",
            url: "../api/order/list",
            params: {
                name: this.props.checkOrdersInfo.name,
                phone: this.props.checkOrdersInfo.contactNumber
            },
            headers: { 'X-CSRF-TOKEN': csrf_token },
            responseType: 'json'
        })
            .then(function (response) {
                if (response.statusText == "OK") {
                    that.setState({ orders: response.data });
                    that.props.toggleLoading(false);
                }
            })
            .catch(function (error) {
                console.log(error);
                that.props.toggleLoading(false);
                that.props.getOrdersError();
                location.href = "../checkOrders/0";
            });
    }
    showDetail(id) {
        let reservation = this.state.orders[id]
        let { t } = this.props
        this.setState({
            showDetail: true,
            detail_data: <Alert notice={t("reserveNotice2")} text={
                t("locations") + ": " + reservation.shop + "\n"
                + t("registrationNumber") + ": " + (reservation.shop == 1 ? "02 2581-3338" : "02 2570-9393") + "\n"
                + t("contactNumber") + ": " + this.props.checkOrdersInfo.contactNumber + "\n"
                + t("reservatorDate") + ": " + reservation.start_time + "\n"
                // + "服務: " + serviceName + "\n"
                + "包廂:" + reservation.service
                + "人數: " + reservation.person + " " + (reservation.person > 1 ? t("people") : t("person")) + "\n"
                + t("reserveNotice1") + "\n"
                + t("reserveNotice2") + '\n'
                + t("reserveNotice3") + "\n" + "本店不可攜帶寵物"} />,
        })
    }

    confirmCancel(e) {
        this.setState({
            showAlert: true,
            alertTitle: "concelConfirm",
            alertText: "orderCanceledConfirm",
            id: e.target.getAttribute("value")
        })
    }

    cancel(id) {
        const that = this,
            csrf_token = document.querySelector('input[name="_token"]').value
        // id = e.target.getAttribute("value");
        that.props.toggleLoading(true);

        axios({
            method: "post",
            url: "../api/order/customer/cancel",
            params: {
                name: this.props.checkOrdersInfo.name,
                phone: this.props.checkOrdersInfo.contactNumber,
                order_id: id
            },
            headers: { 'X-CSRF-TOKEN': csrf_token },
            responseType: 'json'
        })
            .then(function (response) {
                if (response.statusText == "OK") {
                    that.props.toggleLoading(false);
                    that.props.cancelSuccess();
                    that.getOrders();
                }
            })
            .catch(function (error) {
                console.log(error);
                that.props.toggleLoading(false);
                that.props.getOrdersError();
            });
    }
    render() {
        if (this.props.checkOrdersInfo.name === undefined || this.props.checkOrdersInfo.contactNumber === undefined) location.href = '../checkOrders/0';

        const { t } = this.props,
            ths = ["", "", "branch", "time", "service", "guestNum", "operator"].map((th, index) => {
                return (<th key={index}>{t(th)}</th>);
            });

        return (
            <Grid>
                <Row className="show-grid">
                    <Col md={8}>
                        <Table responsive striped bordered condensed hover>
                            <thead>
                                <tr>
                                    {ths}
                                </tr>
                            </thead>
                            <tbody>
                                {this.state.orders.length > 0 ? this.state.orders.map((order, index) => {
                                    return (
                                        <tr>
                                            <td className="detail" onClick={() => { this.showDetail(index) }} >{"內容"}</td>

                                            <td className="cancel" onClick={this.confirmCancel} value={order.id}>{t("cancel")}</td>
                                            <td>{order.shop}</td>
                                            <td>{order.start_time}</td>
                                            <td>{order.service}</td>
                                            <td>{order.person}</td>
                                            <td>{order.service_provider}</td>

                                        </tr>);
                                }) : <td colSpan="5"><p>{t(this.state.hint)}</p></td>}
                            </tbody>
                        </Table>
                    </Col>
                </Row>
                <SweetAlert
                    showCancelButton
                    show={this.state.showAlert}
                    title={t(this.state.alertTitle)}
                    text={t(this.state.alertText)}
                    cancelButtonText="No"
                    confirmButtonText="Yes"
                    onConfirm={() => {
                        console.log('comfirm click', this.state)
                        this.setState({ showAlert: false });
                        this.cancel(this.state.id)
                        // if (this.alertTitle == "Error") { location.href = "../checkOrders/0" }
                    }}
                    onCancel={() => {
                        this.setState({ showAlert: false });
                    }}
                />
                <SweetAlert
                    show={this.state.showDetail}
                    title={t("reserveDetail")}
                    html
                    text={typeof this.state.detail_data == "object" ? renderToStaticMarkup(this.state.detail_data) : this.state.detail_data}
                    onConfirm={() => {
                        this.setState({ showDetail: false });

                    }}
                />

            </Grid>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        loading: state.loading,
        checkOrdersInfo: state.checkOrdersInfo
    }
}

const mapDispatchToProps = (dispatch) => {
    return bindActionCreators({
        toggleLoading: toggleLoading
    }, dispatch);
}

OrdersInfo = connect(mapStateToProps, mapDispatchToProps)(OrdersInfo);

module.exports = translate()(OrdersInfo);