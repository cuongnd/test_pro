<div id="projectUserSelect" style="padding-left: 10px">
	<div class="pad">
		<div class="primary">
			<span class="margin-r5"><?php echo JText::_('COM_BOOKPRO_I_AM_A')?>:</span> <a class="btn grey select margin-r10 authTypeBtn"> <input type="radio" name="userType" id="userTypeNew" value="userTypeNew"  checked="checked"> <span><?php echo JText::_('COM_BOOKPRO_NEW_CUSTOMER')?></span>
			</a> <a class="btn grey select authTypeBtn"> <input type="radio" name="userType" id="userTypeReturning" value="userTypeReturning"> <span><?php echo JText::_('COM_BOOKPRO_RETURNING_CUSTOMER')?></span>
			</a>
			<div id="projectLoginUserCreate" style="display: block;">
				<ul class="sign-up">
					<li class="control-group"><label><?php echo JText::_('COM_BOOKPRO_EMAIL_ADDRESS')?>:</label> <input type="email" value="" class="input-medium" name="email" id="new-email"> <span class="help-inline"></span></li>
					<li class="control-group username"><label><?php echo JText::_('COM_BOOKPRO_NEW_CUSTOMER')?>:</label> <input type="text"  class="input-medium" value="" id="new-username" maxlength="16" name="newusername"> <span class="help-inline"></span></li>
					<div class="clear"></div>
					<li class="control-group"><label><?php echo JText::_('COM_BOOKPRO_PASSWORD')?>:</label> <input type="password" value=""  class="input-medium" id="passwd" name="newuserpasswd"> <span class="help-inline"></span></li>
					<li class="control-group"><label><?php echo JText::_('COM_BOOKPRO_RE_TYPE_ENTER_PASSWORD')?>:</label> <input type="password" class="input-medium" value="" id="passwd1" equalTo="input#passwd" name="newuserpasswd1"> <span class="help-inline"></span></li>
					<div class="clear"></div>
				</ul>
			</div>
			<div style="display: none;" id="userTypeReturningDiv">
				<ul class="sign-up" id="returningForm">
					<li class="control-group"><label><?php echo JText::_('COM_BOOKPRO_CUSTOMER')?>:</label> <input type="text" name="username" size="45" class="input-medium" value="" id="post-proj-username"> <span class="help-inline"></span></li>
					<li class="control-group"><label><?php echo JText::_('COM_BOOKPRO_PASSWORD')?>:</label> <input type="password" class="input-medium" size="45" value="" name="passwd" id="post-proj-pwd"> <span class="help-inline"></span></li>
				</ul>
				<span style="display: none;" id="returningAjax"> <img src="/img/spinner-black.gif" style="width: 30px; margin-top: 60px; margin-left: 250px;">
				</span>
			</div>
		</div>

		<div class="clear"></div>
	</div>
</div>
<style>
.wellcome-customer
{
	width: 100%;
	text-align: center;
}
.pp-box,.wellcome-customer {
    background: -moz-linear-gradient(center top , #FFFFFF 0%, #F9F9F9 100%) repeat scroll 0 0 rgba(0, 0, 0, 0);
    border: 1px solid #CCCCCC;
    border-radius: 4px;
    box-shadow: 0 1px 3px #CCCCCC;
    display: inline-block;
    margin-bottom: 30px;
}
.pp-box .pad {
    padding: 20px;
}
.pp-box .primary {
    border-right: 1px solid #CCCCCC;
    float: left;
    width: 415px;
}
.pp-box .secondary {
    border-right: 1px solid #FFFFFF;
    float: right;
    height: 178px;
    text-align: center;
    transition: all 0.2s ease 0s;
    width: 310px;
}
.btn {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: #F5F5F5;
    background-image: -moz-linear-gradient(center top , #FFFFFF, #E6E6E6);
    background-repeat: repeat-x;
    border-color: #CCCCCC #CCCCCC #B3B3B3;
    border-image: none;
    border-radius: 2px;
    border-style: solid;
    border-width: 1px;
    box-shadow: 0 1px 0 rgba(255, 255, 255, 0.2) inset, 0 1px 2px rgba(0, 0, 0, 0.05);
    color: #333333;
    cursor: pointer;
    display: inline-block;
    font-size: 13px;
    line-height: 18px;
    margin-bottom: 0;
    padding: 4px 10px;
    text-align: center;
    text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
    vertical-align: middle;
}
a.connect.lrg{

    background:url("/static/css/images/facebook-f-lrg.png?v=ca808cecc80c35dcb50cee3ab1762bed&amp;m=2") no-repeat scroll 0 12px rgba(0, 0, 0, 0);
    height: 49px;
    background-color: #5677AD;
    line-height: 52px;
    margin: -8px 16px -8px 0;
    padding: 0 14px;
  background: linear-gradient(#6D80A6, #4E6293) repeat scroll 0 0 rgba(0, 0, 0, 0);
  border: 1px solid #2E4464;
  border-radius: 3px;
  color: #FFFFFF !important;
  cursor: pointer;
  display: inline-block;
  font-family: 'Lucida Grande','Helvetica Neue',Helvetica,Arial,sans-serif;
  font-size: 11px;
  font-weight: 700;
  line-height: 1;
  position: relative;
  text-decoration: none;
  text-shadow: 0 -1px 0 rgba(0, 0, 20, 0.4);
}
input[type="image"], input[type="checkbox"], input[type="radio"] {
    border-radius: 0;
    cursor: pointer;
    height: auto;
    line-height: normal;
    margin: 3px 5px 3px 0;
    padding: 0;
    width: auto;
}
ul.sign-up {
    list-style: none outside none;
    margin: 30px 0 0;
    padding: 0;
}
ul.sign-up li {
    float: left;
    list-style: none outside none;
    margin-right: 20px;
    width: 42%;
}
.control-group {
    margin-bottom: 9px;
}
.pp-box .secondary .pad {
    padding-top: 25px !important;
}
.pp-box .pad {
    padding: 20px;
}

</style>