<?
/**
 *Parameter default, selalu ada di setiap request
 */
$cmd		=$_REQUEST[cmd];
$pid		=$_REQUEST[pid];	// portal id(pkbl, sdm, oa, eis, public, aset) ldap->sn
$ppwd		=$_REQUEST[ppwd]; 	// portal password_id
$userid		=$_REQUEST[userid];	// user yang me-request

/**
 *parameter lainnya 
 */
$username	=$_REQUEST[username];	// ldap->cn
$password	=$_REQUEST[password];	
$fullname	=$_REQUEST[fullname];	//daniel 10/22/2008
$level		=$_REQUEST[level];	// ldap->employee type
$bumn_id	=$_REQUEST[bumn_id]; 	// bila usernya kn-bumn bumn_id='0000', ldap->departmentNumber
$emp_id		=$_REQUEST[emp_id];	// khusus untuk portal oa, ldap->employeeNumber
$email		=$_REQUEST[email];
$phone		=$_REQUEST[phone];
$dp_no		=$_REQUEST[dp_no];

$offset		=($_REQUEST[offset]) ? $_REQUEST[offset] : 0 ;
$limit		=($_REQUEST[limit]) ? $_REQUEST[limit] : 20 ;

$oldpwd		=$_REQUEST[oldpwd];
$newpwd		=$_REQUEST[newpwd];

$filter		=$_REQUEST[filter];
$keyword	=$_REQUEST[keyword];

/*********************************************************************************
 * Attribut server ldap
 * 
 * businessCategory = portal (eis, oa, pkbl, sdm)
 * cn, sn, uid		= username
 * employeeType		= level (au, as, us)
 * mail				= email
 * ou				= bumn_id
 * Phone			= telepon
 * userPassword		= password md5 
 * departmentNumber	= departemen number
 *********************************************************************************/

$i = new services();
switch ($cmd)
{
	case 'search_user':
		$i->searchUser($keyword);
		break;



		
		
		
	case 'add_user':
		$i->addUser($username, $password, $level, $pid, $bumn_id, $emp_id, $email, $phone, $dp_no, $fullname);
		break;
	case 'add_portal':
		$i->addPortal($username, $pid);
		break;
	case 'edit_user':
		$i->editUser($username, $email, $phone, $emp_id, $fullname);
		break;
	case 'view_user':
		$i->viewUser($username);
		break;
	case 'delete_portal':
		$i->deletePortal($username, $pid);
		break;
	case 'delete_user':
		$i->deleteUser($username);
		break;
	case 'chpwd':
		$i->chPwd($username, $oldpwd, $newpwd);
		break;
	case 'respwd':
		$i->resPwd($username);
		break;
	case 'edpwd':
		$i->chPwd($username, "", $newpwd, true);
		break;
	case 'list_au':
		$i->searchUser("as","","", $offset, $limit);
		break;
	case 'list_as':
		$i->searchUser("us","bumn_id",$bumn_id, $offset, $limit);
		break;
		//cmd=search_au&filter=bumn_id&keyword=BMDR
		//cmd=search_au&filter=username&keyword=iwanmandiri
	case 'search_au':
		$i->searchUser("as", $filter, $keyword, $offset, $limit);
		break;
		//ref=kelola&pid=eis&user_id=sys_BMDR&ppwd=93563a4fdef66b26fe9386720e7389ba&cmd=search_as&keyword=iwan
	case 'search_as':
		$i->searchUser("us", "username", $keyword, $offset, $limit);
		break;
	case 'search_emp':
		$i->searchUser("us", "emp_id", $keyword, $offset, $limit);
		break;
	case 'list_nonmember_au':
		$i->listNonMember($pid, $username, "as", "", $offset, $limit);
		break;
	case 'list_nonmember_as':
		$i->listNonMember($pid, $username, "us", $bumn_id, $offset, $limit);
		break;
	case 'cekkeyword':
		$i->cekKeyword($username);
		break;
	case 'user_bumn':
		$i->userBumn($username);
		break;
	case 'edit_org':
		$i->editOrg($emp_id, $dp_no);
		break;
	case 'search_dp':
		$i->searchUser("us", "dp_no", $keyword, $offset, $limit);
		break;
	default:
		echo "unknown command $cmd";
		break;
}

?>
