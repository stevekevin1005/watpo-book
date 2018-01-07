import { LinkContainer } from 'react-router-bootstrap';
import { translate } from 'react-i18next';
import i18n from '../i18n';
import {connect} from "react-redux";
import clearReservation from "../dispatchers/clearReservation";
import clearSourceData from "../dispatchers/clearSourceData";
import {bindActionCreators} from "redux";

// 首頁

const Button = ReactBootstrap.Button,
      Grid = ReactBootstrap.Grid,
      Row = ReactBootstrap.Row,
      Col = ReactBootstrap.Col;

class Landpage extends React.Component{
    constructor(props){
        super(props);
    }
    componentDidMount(){
        if(this.props.reservation !== null){
            this.props.clearReservation("all");
            this.props.clearSourceData("timeList");
            this.props.clearSourceData("selectedDetail");
        }
    }
    render(){
        const { t } = this.props;
        const map_style = {
            width: "100%",
            height: 450,
            border: 0
        };
        const branchData = [{
            name: t("location1"),
            time: "12:00 p.m. ~ 04:00 a.m.",
            address: t('watpoAddr1'),
            phone: "( 02 ) 2581- 3338",
            location_src: "https://www.google.com/maps/embed/v1/place?key=AIzaSyDF_ECD1dnac71XWyss7Asu_Q15pb7HbF4&q=place_id:ChIJD_320V2pQjQR7q6lHg9dZaA"
        },{
            name: t("location2"),
            time: "11:00 a.m. ~ 03:00 a.m.",
            address: t('watpoAddr2'),
            phone: "( 02 ) 2570- 9393",
            location_src: "https://www.google.com/maps/embed/v1/place?key=AIzaSyDF_ECD1dnac71XWyss7Asu_Q15pb7HbF4&q=place_id:ChIJdbQig-qrQjQRA7uNkj6tkc4"
        }],
        branches = branchData.map((branch, index)=>{
            return (
                <div>
                    <h4><i className="fa fa-caret-right" aria-hidden="true"></i>{" "+branch.name}</h4>
                    <Row>
                        <Col md={6}>
                            <div className="contentBlock" key={index}>
                                <p>
                                {t('bussinessHours') + " : " +branch.time}<br/>
                                {t('address') + " : " +branch.address}<br/>
                                {t('registrationNumber') + " : " +branch.phone}
                                </p>
                            </div>
                        </Col>
                        <Col md={6}>
                            <iframe
                              className="sectionItem"
                              frameBorder="0" 
                              style={map_style}
                              src={branch.location_src} allowFullScreen>
                            </iframe>
                        </Col>
                    </Row>  
                </div>
            );
        });

        return(
              <Grid>
                <Row className="landpage_top">
                    <div className="topContainer">
                    <h1 className="title">{t("Watpo")}</h1>
                    <h2 className="sub">{t("ThaiTraditionalMedicalMassage")}</h2>
                        <div className="landpageBtnContainer">
                        <LinkContainer to="/reservation/0">
                            <Button bsStyle="primary" bsSize="large" className="btn mainBtn" block>
                                <i className="fa fa-pencil" aria-hidden="true"></i>
                                {"  " + t('book')}
                            </Button>
                        </LinkContainer>
                        <LinkContainer to="/book/check/0">
                            <Button bsStyle="info" bsSize="large" className="btn mainBtn" block>
                                <i className="fa fa-search" aria-hidden="true"></i>
                                {"  " + t('book check')}
                            </Button>
                        </LinkContainer>
                        </div>
                    </div>
                </Row>
                <Row className="landpage_section">
                    <Col md={12}>
                        <h3 className="sectionTitle"><i className="fa fa-list-alt" aria-hidden="true"></i>{" " + t("services")}</h3>
                    </Col>
                    <Col md={5}>
                        <div className="img"></div>
                    </Col>
                    <Col md={7}>
                        <div className="sectionItem">
                            <h4><i className="fa fa-caret-right" aria-hidden="true"></i>{t("ThaiTraditionalMassage")}</h4>
                            <h5>{t("price1") + ": " + t("servicePrice1_1hr")+ " (1" + t("hour") +") / "+ t("servicePrice1_2hr")+ " (2" + t("hours") +")"}</h5>
                            <div className="contentBlock">
                                <p>
                                {t("ThaiTraditionalMassageDes")}
                                </p>
                            </div>
                        </div>
                        <div className="sectionItem">
                            <h4><i className="fa fa-caret-right" aria-hidden="true"></i> {t("ThaiOilMassage")}</h4>
                            <h5>{t("price2") + ": " + t("servicePrice2_1hr")+ " (1" + t("hour") +") / "+ t("servicePrice2_2hr")+ " (2" + t("hours") +")"}</h5>
                            <div className="contentBlock">
                                <p>
                                    {t("ThaiOilMassageDes")}
                                </p>
                            </div>
                        </div>

                        <div className="sectionItem">
                            <h4><i className="fa fa-caret-right" aria-hidden="true"></i> {t("massageAndSpa")}</h4>
                            <h5>{t("price3") + ": " + t("servicePrice3") + " (2" + t("hours") +")"}</h5>
                            <div className="contentBlock">    
                                <p>
                                {t("massageAndSpaDes")}
                                </p>
                            </div>
                        </div>
                    </Col>
                </Row>
                <Row className="landpage_section">
                <Col md={12}>
                    <h3 className="sectionTitle"><i className="fa fa-building-o" aria-hidden="true"></i>{" "+t("locations")}</h3>
                </Col>
                    <Col md={12}>
                        {branches}
                    </Col>
                </Row>
                <div className="topBg" data-arrow="&#xf078;"></div>
                <Row className="footerContainer">
                <Col md={12}>
                <footer>
                    <a href="https://www.facebook.com/watpomassages" target="_blank"><i className="fa fa-facebook-square" aria-hidden="true"></i></a><br/>
                    {t("WatpoThaiTraditionalMedicalMassage")}<br/>
                    {t("registrationNumber")}: ( 02 ) 2581- 3338<br/>
                    {t("bussinessHours")}: 12:00 p.m. ~ 04:00 a.m.<br/>
                    {t("watpoAddr1")}
                </footer>
                </Col>
                </Row>
              </Grid>
        );
    }
}

const mapDispatchToProps = (dispatch)=>{
    return bindActionCreators({
        clearReservation: clearReservation,
        clearSourceData: clearSourceData
    },dispatch);
}
  
Landpage = connect(null, mapDispatchToProps)(Landpage);

export default translate()(Landpage); 
