// 負責寫資料(日期,時段)到global state
import { translate } from 'react-i18next';
import {connect} from "react-redux";
import {bindActionCreators} from "redux";
import toggleLoading from "../../dispatchers/toggleLoading";

const Grid = ReactBootstrap.Grid,
    Row = ReactBootstrap.Row,
    Col = ReactBootstrap.Col,
    ListGroupItem = ReactBootstrap.ListGroupItem,
    ListGroup = ReactBootstrap.ListGroup,
    Table = ReactBootstrap.Table;

class OrdersInfo extends React.Component{
    constructor(props){
        super(props);

        this.state = {
            hint: "ordersInfoHint",
            orders: []
        };
        this.cancel = this.cancel.bind(this);
        this.getOrders = this.getOrders.bind(this);
    }
    componentDidMount(){
        this.getOrders();
    }
    getOrders(){
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
            headers: {'X-CSRF-TOKEN': csrf_token},
            responseType: 'json'
        })
        .then(function (response) {
            if(response.statusText == "OK"){
                that.setState({orders: response.data});
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
    cancel(e){
        const that = this,
              csrf_token = document.querySelector('input[name="_token"]').value,
              id = e.target.getAttribute("value");
        that.props.toggleLoading(true);

        axios({
            method: "post",
            url: "../api/order/customer/cancel",
            params: {
                name: this.props.checkOrdersInfo.name,
                phone: this.props.checkOrdersInfo.contactNumber,
                order_id: id
            },
            headers: {'X-CSRF-TOKEN': csrf_token},
            responseType: 'json'
        })
        .then(function (response) {
            if(response.statusText == "OK"){
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
    render(){
        if(this.props.checkOrdersInfo.name === undefined || this.props.checkOrdersInfo.contactNumber === undefined) location.href = '../checkOrders/0';
        
        const { t } = this.props,
        ths = ["name","guestNum","time",""].map((th, index)=>{
            return (<th key={index}>{t(th)}</th>);
        });

        return(
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
                    {this.state.orders.length>0?this.state.orders.map((order,index)=>{
                        return (
                            <tr>
                                <td>{order.name}</td>
                                <td>{order.person}</td>
                                <td>{order.start_time}</td>
                                <td className="cancel" onClick={this.cancel} value={order.id}>{t("cancel")}</td>
                            </tr>);
                    }):<td colSpan="5"><p>{t(this.state.hint)}</p></td>}
                    </tbody>
                </Table>
            </Col>
            </Row>
            </Grid>
        );
    }
}

const mapStateToProps = (state)=>{
    return {
        loading: state.loading,
        checkOrdersInfo: state.checkOrdersInfo
    }
}

const mapDispatchToProps = (dispatch)=>{
    return bindActionCreators({
        toggleLoading: toggleLoading
    },dispatch);
  }
  
OrdersInfo = connect(mapStateToProps,mapDispatchToProps)(OrdersInfo);  

module.exports = translate()(OrdersInfo);