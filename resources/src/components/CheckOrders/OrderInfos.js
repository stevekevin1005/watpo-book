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
            orders: null
        };
        this.cancel = this.cancel.bind(this);
        this.getOrders = this.getOrders.bind(this);
    }
    componentDidMount(){
        this.getOrders();
    }
    cancel(e){
        const that = this,
        csrf_token = document.querySelector('input[name="_token"]').value;
        that.props.toggleLoading(true);
        axios({
            method: "post",
            url: "../api/customer/cancel",
            params: {
                order_id: e.target.value,
                name: this.props.checkOrdersInfo.name,
                phone: this.props.checkOrdersInfo.contactNumber
            },
            headers: {'X-CSRF-TOKEN': csrf_token},
            responseType: 'json'
        })
        .then(function (response) {
            console.log(response);
            if(response.statusText == "OK"){
                that.setState({orders: response.data});
                that.props.toggleLoading(false);
                this.props.cancelSuccess();
            }
        })
        .catch(function (error) {
            console.log(error);
            that.props.toggleLoading(false);
            that.props.getOrdersError();
        });        
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
            console.log(response);
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
    render(){
        if(this.props.checkOrdersInfo.name === undefined || this.props.checkOrdersInfo.contactNumber === undefined) location.href = '../checkOrders/0';
        
        const { t } = this.props,
        ths = ["name","phone","guesetNum","time",""].map((th, index)=>{
            return (<th key={index}>{t(th)}</th>);
        });

        return(
            <Grid>
            <Row className="show-grid">
            <Col md={12}>
                <Table responsive>
                    <thead>
                        {ths}
                    </thead>
                    <div>
                        {this.state.orders?this.state.orders.map((order,index)=>{
                            return (<tr>
                                <td>{order.name}</td>
                                <td>{order.phone}</td>
                                <td></td>
                                <td></td>
                                <td className="cancel" onClick={this.cancel} value={order.id}>{t("cancel")}</td>
                                </tr>);
                        }):<p>{t(this.state.hint)}</p>}
                    </div>
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