// 負責寫資料(日期,時段)到global state
import { translate } from 'react-i18next';
import {connect} from "react-redux";
import {bindActionCreators} from "redux";
import toggleLoading from "../../dispatchers/toggleLoading";

const Grid = ReactBootstrap.Grid,
    Row = ReactBootstrap.Row,
    Col = ReactBootstrap.Col,
    ListGroupItem = ReactBootstrap.ListGroupItem,
    ListGroup = ReactBootstrap.ListGroup;

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
    cancel(){
        // call API
        this.props.cancelSuccess();
        this.getOrders();
    }
    getOrders(){
        const that = this,
        csrf_token = document.querySelector('input[name="_token"]').value;
        that.props.toggleLoading(true);
        // 取得預約資料列表
        axios({
            method: "get",
            url: "../api/shop_list",
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
        });
    }
    render(){
        if(this.props.checkOrdersInfo.name === undefined || this.props.checkOrdersInfo.contactNumber === undefined) location.href = '../checkOrders/0';

        const { t } = this.props;
        return(
            <Grid>
            <Row className="show-grid">
            <Col md={12}>
                <div className="timePeriods">
                    {this.state.orders?this.state.orders.map((order,index)=>{
                        return (<div className="orderInfo"><p>{order.description}</p><div className="cancel" onClick={this.cancel}>{t("cancel")}</div></div>);
                    }):<p>{t(this.state.hint)}</p>}
                </div>
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