<?php
/**
 * BuddyPress - Groups Home
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>
<div id="buddypress">

	<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>

	<?php

	/**
	 * Fires before the display of the group home content.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_group_home_content' ); ?>

	<div id="item-header" role="complementary">

		<?php
		/**
		 * If the cover image feature is enabled, use a specific header
		 */
		if ( bp_group_use_cover_image_header() ) :
			bp_get_template_part( 'groups/single/cover-image-header' );
		else :
			bp_get_template_part( 'groups/single/group-header' );
		endif;
		?>

	</div><!-- #item-header -->

	<div id="item-nav">
		<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
			<ul>

				<?php bp_get_options_nav(); ?>

				<?php

				/**
				 * Fires after the display of group options navigation.
				 *
				 * @since 1.2.0
				 */
				do_action( 'bp_group_options_nav' ); ?>

			</ul>
		</div>
	</div><!-- #item-nav -->

	<div id="item-body">

		<?php

		/**
		 * Fires before the display of the group home body.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_before_group_body' );

		/**
		 * Does this next bit look familiar? If not, go check out WordPress's
		 * /wp-includes/template-loader.php file.
		 *
		 * @todo A real template hierarchy? Gasp!
		 */

			// Looking at home location
			if ( bp_is_group_home() ) :
				if ( bp_group_is_visible() ) { ?>
				<div>
					<h3>
						Welcome to your group. <a href="rendez-vous">Rendez-vous</a> with your teammates and select the role you want to perform below . 
						Then go to <a href="docs">Docs</a> tab to start working on your group activity.
					</h3>
					<br>
				</div>



				
				<?php if ( bp_group_has_members( 'exclude_admins_mods=0' ) ) : ?>

	<?php

	/**
	 * Fires before the display of the group members content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_members_content' ); ?>


	<?php

	/**
	 * Fires before the display of the group members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_members_list' ); ?>

	<div>
		<h5>
			<font color="blue" >Current User roles are - 
			<?php
				$dbhost = 'localhost:3306';
				$dbuser = 'root';
				$dbpass = 'admin';
				$dbname = 'wordpress';

				$conn = mysql_connect($dbhost, $dbuser, $dbpass);
				mysql_select_db('wordpress');
	            $groupname = bp_get_current_group_slug();

				$sqlgrp = "SELECT username, role FROM wp_group_user_roles WHERE groupname ='$groupname'";
				$retgrp = mysql_query( $sqlgrp, $conn );

				if(! $retgrp )
				{
				  die('Could not get data: ' . mysql_error());
				}
				while($row_grp = mysql_fetch_assoc($retgrp))
				{
				    echo "{$row_grp['username']} : {$row_grp['role']} ; ";			         
				}

				mysql_close($conn);
			?>
		</font></h5>
	</div>

	<?php
	if(isset($_POST['add'])) {
			$dbhost = 'localhost:3306';
			$dbuser = 'root';
			$dbpass = 'admin';
			$dbname = 'wordpress';

			$conn = mysql_connect($dbhost, $dbuser, $dbpass);
			mysql_select_db('wordpress');
        	$srole = $_POST['srole'];
            $groupname = bp_get_current_group_slug();
            $current_user = wp_get_current_user(); 
			$current_username = $current_user->user_login;

			$sqlget = "SELECT username FROM wp_group_user_roles WHERE role='$srole'";
			$sqlgetrole = "SELECT role FROM wp_group_user_roles WHERE username='$current_username'";
            $retvalget = mysql_query( $sqlget, $conn );
            $retvalgetrole = mysql_query( $sqlgetrole, $conn );
            $row_get = mysql_fetch_assoc($retvalget);
            $row_getrole = mysql_fetch_assoc($retvalgetrole);
            $existuser = $row_get["username"];
            $existrole = $row_getrole["role"];

            if($existrole != ""){
            	echo "<font color='red'><b> You already have role of ".$existrole."</b></font>";
            }else if($existuser != ""){
            	echo "<font color='red'><b> The role has already been taken by ".$existuser."</b></font>";
            }else{
            	$sql = "INSERT INTO wp_group_user_roles ". "(groupname, username, role) ". "VALUES('$groupname','$current_username', '$srole')";
            

	           	mysql_select_db('wordpress');
	            $retval = mysql_query( $sql, $conn );
	            
	            if(! $retval ) {
	               die('Could not enter data: ' . mysql_error());
	            }else{
	            	echo "<font color='green'><b> Role successfully updated - go to Docs Tab to start working on your group activity\n </b></font>";
	            }                       
            }            
            
            mysql_close($conn);
        }
	?>

	<?php
	if(isset($_POST['remove'])) {
			$dbhost = 'localhost:3306';
			$dbuser = 'root';
			$dbpass = 'admin';
			$dbname = 'wordpress';

			$conn = mysql_connect($dbhost, $dbuser, $dbpass);
			mysql_select_db('wordpress');
            $current_user = wp_get_current_user(); 
			$current_username = $current_user->user_login;
			$groupname = bp_get_current_group_slug();

			$sql_del = "DELETE  FROM wp_group_user_roles WHERE username='$current_username' and groupname='$groupname'";			                           

	           	mysql_select_db('wordpress');
	            $retval_del = mysql_query( $sql_del, $conn );
	            
	            if( !$retval_del ) {
	               echo "<font color='red'><b>There was some problem in deleting the role\n</b></font>";
	            }else{
	            	if(mysql_affected_rows() == 0){
	            		echo "<font color='red'><b> There is no role to delete \n</b></font>";
	            	}else{
	            		echo "<font color='green'><b> Role delete successfully \n</b></font>";	
	            	}

	            }                       
            
            mysql_close($conn);
        }
	?>

	<ul id="member-list" class="item-list">

		<?php $user_array = array(); ?>
		<?php while ( bp_group_members() ) : bp_group_the_member(); 

				global $members_template; 
						$user = new WP_User( $members_template->member->user_login );
						if($user->roles[0] != 'teacher'){
							array_push($user_array, $members_template->member->user_login);
						}							

				

		endwhile; ?>

		<?php
			$dbhost = 'localhost:3306';
$dbuser = 'root';
$dbpass = 'admin';
$dbname = 'wordpress';

$conn = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db('wordpress');
            
$sql_idea = "SELECT AVG(points) FROM wp_grade_table where role='Idea' and username='".$user_array[0]."'";
$sql_writer = "SELECT AVG(points) FROM wp_grade_table where role='Writer' and username='".$user_array[0]."'";
$sql_editor = "SELECT AVG(points) FROM wp_grade_table where role='Editor' and username='".$user_array[0]."'";
$sql_proof = "SELECT AVG(points) FROM wp_grade_table where role='Proof' and username='".$user_array[0]."'";

$retval_idea = mysql_query( $sql_idea, $conn );
$retval_writer = mysql_query( $sql_writer, $conn );
$retval_editor = mysql_query( $sql_editor, $conn );
$retval_proof = mysql_query( $sql_proof, $conn );

$row_idea = mysql_fetch_assoc($retval_idea);
$avg_idea = $row_idea["AVG(points)"];
$user1_message = "<b>".$user_array[0]. "</b> has not taken the following roles and can try these roles : ";
if($avg_idea == ""){
    $avg_idea = 0;
    $user1_message = $user1_message." Idea; ";
}

$row_writer = mysql_fetch_assoc($retval_writer);
$avg_writer = $row_writer["AVG(points)"];
if($avg_writer == ""){
    $avg_writer = 0;
    $user1_message = $user1_message." Writer; ";
}

$row_editor = mysql_fetch_assoc($retval_editor);
$avg_editor = $row_editor["AVG(points)"];
if($avg_editor == ""){
    $avg_editor = 0;
    $user1_message = $user1_message." Editor; ";
}

$row_proof = mysql_fetch_assoc($retval_proof);
$avg_proof = $row_proof["AVG(points)"];
if($avg_proof == ""){
    $avg_proof = 0;
    $user1_message = $user1_message." Proof Reader; ";
}


$sql_idea1 = "SELECT AVG(points) FROM wp_grade_table where role='Idea' and username='".$user_array[1]."'";
$sql_writer1 = "SELECT AVG(points) FROM wp_grade_table where role='Writer' and username='".$user_array[1]."'";
$sql_editor1 = "SELECT AVG(points) FROM wp_grade_table where role='Editor' and username='".$user_array[1]."'";
$sql_proof1 = "SELECT AVG(points) FROM wp_grade_table where role='Proof' and username='".$user_array[1]."'";


$retval_idea1 = mysql_query( $sql_idea1, $conn );
$retval_writer1 = mysql_query( $sql_writer1, $conn );
$retval_editor1 = mysql_query( $sql_editor1, $conn );
$retval_proof1 = mysql_query( $sql_proof1, $conn );

$row_idea1 = mysql_fetch_assoc($retval_idea1);
$avg_idea1 = $row_idea1["AVG(points)"];
$user2_message = "<b>".$user_array[1]. "</b> has not taken the following roles and can try these roles : ";
if($avg_idea1 == ""){
    $avg_idea1 = 0;
    $user2_message = $user2_message." Idea; ";
}

$row_writer1 = mysql_fetch_assoc($retval_writer1);
$avg_writer1 = $row_writer1["AVG(points)"];
if($avg_writer1 == ""){
    $avg_writer1 = 0;
    $user2_message = $user2_message." Writer; ";
}

$row_editor1 = mysql_fetch_assoc($retval_editor1);
$avg_editor1 = $row_editor1["AVG(points)"];
if($avg_editor1 == ""){
    $avg_editor1 = 0;
    $user2_message = $user2_message." Editor; ";
}

$row_proof1 = mysql_fetch_assoc($retval_proof1);
$avg_proof1 = $row_proof1["AVG(points)"];
if($avg_proof1 == ""){
    $avg_proof1 = 0;
    $user2_message = $user2_message." Proof Reader; ";
}


$sql_idea2 = "SELECT AVG(points) FROM wp_grade_table where role='Idea' and username='".$user_array[2]."'";
$sql_writer2 = "SELECT AVG(points) FROM wp_grade_table where role='Writer' and username='".$user_array[2]."'";
$sql_editor2 = "SELECT AVG(points) FROM wp_grade_table where role='Editor' and username='".$user_array[2]."'";
$sql_proof2 = "SELECT AVG(points) FROM wp_grade_table where role='Proof' and username='".$user_array[2]."'";


$retval_idea2 = mysql_query( $sql_idea2, $conn );
$retval_writer2 = mysql_query( $sql_writer2, $conn );
$retval_editor2 = mysql_query( $sql_editor2, $conn );
$retval_proof2 = mysql_query( $sql_proof2, $conn );

$user3_message = "<b>".$user_array[2]. "</b> has not taken the following roles and can try these roles : ";
$row_idea2 = mysql_fetch_assoc($retval_idea2);
$avg_idea2 = $row_idea2["AVG(points)"];
if($avg_idea2 == ""){
    $avg_idea2 = 0;
    $user3_message = $user3_message." Idea; ";
}

$row_writer2 = mysql_fetch_assoc($retval_writer2);
$avg_writer2 = $row_writer2["AVG(points)"];
if($avg_writer2 == ""){
    $avg_writer2 = 0;
    $user3_message = $user3_message." Writer; ";
}

$row_editor2 = mysql_fetch_assoc($retval_editor2);
$avg_editor2 = $row_editor2["AVG(points)"];
if($avg_editor2 == ""){
    $avg_editor2 = 0;
    $user3_message = $user3_message." Editor; ";
}

$row_proof2 = mysql_fetch_assoc($retval_proof2);
$avg_proof2 = $row_proof2["AVG(points)"];
if($avg_proof2 == ""){
    $avg_proof2 = 0;
    $user3_message = $user3_message." Proof Reader; ";
}


$sql_idea3 = "SELECT AVG(points) FROM wp_grade_table where role='Idea' and username='".$user_array[3]."'";
$sql_writer3 = "SELECT AVG(points) FROM wp_grade_table where role='Writer' and username='".$user_array[3]."'";
$sql_editor3 = "SELECT AVG(points) FROM wp_grade_table where role='Editor' and username='".$user_array[3]."'";
$sql_proof3 = "SELECT AVG(points) FROM wp_grade_table where role='Proof' and username='".$user_array[3]."'";


$retval_idea3 = mysql_query( $sql_idea3, $conn );
$retval_writer3 = mysql_query( $sql_writer3, $conn );
$retval_editor3 = mysql_query( $sql_editor3, $conn );
$retval_proof3 = mysql_query( $sql_proof3, $conn );

$user4_message = "<b>".$user_array[3]. "</b> has not taken the following roles and can try these roles : ";
$row_idea3 = mysql_fetch_assoc($retval_idea3);
$avg_idea3 = $row_idea3["AVG(points)"];
if($avg_idea3 == ""){
    $avg_idea3 = 0;
    $user4_message = $user4_message." Idea; ";
}

$row_writer3 = mysql_fetch_assoc($retval_writer3);
$avg_writer3 = $row_writer3["AVG(points)"];
if($avg_writer3 == ""){
    $avg_writer3 = 0;
    $user4_message = $user4_message." Writer; ";
}

$row_editor3 = mysql_fetch_assoc($retval_editor3);
$avg_editor3 = $row_editor3["AVG(points)"];
if($avg_editor3 == ""){
    $avg_editor3 = 0;
    $user4_message = $user4_message." Editor; ";
}

$row_proof3 = mysql_fetch_assoc($retval_proof3);
$avg_proof3 = $row_proof3["AVG(points)"];
if($avg_proof3 == ""){
    $avg_proof3 = 0;
    $user4_message = $user4_message." Proof Reader; ";
}




echo "<br><br>";?>

<h5> 
	Recommended roles for the users in your group are:
</h5>

<?php

    $student1 = array( $avg_idea, $avg_writer, $avg_editor, $avg_proof );
    $student2 = array( $avg_idea1, $avg_writer1 , $avg_editor1, $avg_proof1 );
    $student3 = array( $avg_idea2, $avg_writer2 , $avg_editor2, $avg_proof2 );
    $student4 = array( $avg_idea3, $avg_writer3 , $avg_editor3, $avg_proof3 );
    
    $sums = array(
    '0123' => $student1[0] + $student2[1] + $student3[2] + $student4[3],
    '0132' => $student1[0] + $student2[1] + $student3[3] + $student4[2],
    '0213' => $student1[0] + $student2[2] + $student3[1] + $student4[3],
    '0231' => $student1[0] + $student2[2] + $student3[3] + $student4[1],
    '0312' => $student1[0] + $student2[3] + $student3[1] + $student4[2],
    '0321' => $student1[0] + $student2[3] + $student3[2] + $student4[1],
    '1023' => $student1[1] + $student2[0] + $student3[2] + $student4[3],
    '1032' => $student1[1] + $student2[0] + $student3[3] + $student4[2],
    '1203' => $student1[1] + $student2[2] + $student3[0] + $student4[3],
    '1230' => $student1[1] + $student2[2] + $student3[3] + $student4[0],
    '1320' => $student1[1] + $student2[3] + $student3[2] + $student4[0],
    '1302' => $student1[1] + $student2[3] + $student3[0] + $student4[2],
    '2013' => $student1[2] + $student2[0] + $student3[1] + $student4[3],
    '2031' => $student1[2] + $student2[0] + $student3[3] + $student4[1],
    '2103' => $student1[2] + $student2[1] + $student3[0] + $student4[3],
    '2130' => $student1[2] + $student2[1] + $student3[3] + $student4[0],
    '2301' => $student1[2] + $student2[3] + $student3[0] + $student4[1],
    '2310' => $student1[2] + $student2[3] + $student3[1] + $student4[0],
    '3012' => $student1[3] + $student2[0] + $student3[1] + $student4[2],
    '3021' => $student1[3] + $student2[0] + $student3[2] + $student4[1],
    '3102' => $student1[3] + $student2[1] + $student3[0] + $student4[2],
    '3120' => $student1[3] + $student2[1] + $student3[2] + $student4[0],
    '3201' => $student1[3] + $student2[2] + $student3[0] + $student4[1],
    '3210' => $student1[3] + $student2[2] + $student3[1] + $student4[0]
    );
    
    $maxVal = 0;
    foreach($sums as $key => $value){
        if($value > $maxVal){
            $maxVal = $value;
            $max = $key;
        }
    }
    $max = $max ."";
    

    for($x = 0; $x<4; $x++){
        if($max[$x] == "0"){
            if($x == 0){
                echo "<b>".$user_array[0]."</b> : ";
            }else if($x == 1){
                echo "<b>".$user_array[1]."</b> : ";
            }else if($x == 2){
                echo "<b>".$user_array[2]."</b> : ";
            } else{
                echo "<b>".$user_array[3]. "</b> : ";
            }
             echo "idea <br>";
        }else if($max[$x] == "1"){
            if($x == 0){
                echo "<b>".$user_array[0]."</b> : ";
            }else if($x == 1){
                echo "<b>".$user_array[1]."</b> : ";
            }else if($x == 2){
                echo "<b>".$user_array[2]."</b> : ";
            } else{
                echo "<b>".$user_array[3]. "</b> : ";
            }

             echo "writer <br>";
        }else if($max[$x] == "2"){
            if($x == 0){
                echo "<b>".$user_array[0]."</b> : ";
            }else if($x == 1){
                echo "<b>".$user_array[1]."</b> : ";
            }else if($x == 2){
                echo "<b>".$user_array[2]."</b> : ";
            } else{
                echo "<b>".$user_array[3]. "</b> : ";
            }

            echo "editor <br>";
        }else{
            if($x == 0){
                echo "<b>".$user_array[0]."</b> : ";
            }else if($x == 1){
                echo "<b>".$user_array[1]."</b> : ";
            }else if($x == 2){
                echo "<b>".$user_array[2]."</b> : ";
            } else{
                echo "<b>".$user_array[3]. "</b> : ";
            }

            echo "proof reader <br>";
        }
    }
		?>

		<br>
		<?php
			if( $user1_message != "<b>".$user_array[0]. "</b> has not taken the following roles and can try these roles : "){
				echo $user1_message."<br>";	
			}
			if( $user2_message != "<b>".$user_array[1]. "</b> has not taken the following roles and can try these roles : "){
				echo $user2_message."<br>";	
			}
			if( $user3_message != "<b>".$user_array[2]. "</b> has not taken the following roles and can try these roles : "){
				echo $user3_message."<br>";	
			}

			if( $user4_message != "<b>".$user_array[3]. "</b> has not taken the following roles and can try these roles : "){
				echo $user4_message."<br>";	
			}
		?>



	</ul>

	<br>

	<form method = "post">
	
	<div class="row">
		<span><b>Select your role :  <b></span>
		<select name = "srole" id="srole">
		  <option value="Idea">Idea Generator</option>
		  <option value="Writer">Writer</option>
		  <option value="Editor">Editor</option>
		  <option value="Proof">ProofReader</option>
		</select>
	</div>

	<div class="row" style = "padding-top : 15px">
                           <input name = "add" class="btn btn-secondary" type="submit" id = "add" 
                              value = "Add Role">
</div>
</form>

<form method = "post">
	<div class="row" style = "padding-top : 15px">
                           <input name = "remove" class="btn btn-secondary" type="submit" id = "remove" 
                              value = "Remove current Role">
	</div>
</form>

	<?php

	/**
	 * Fires after the display of the group members content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_group_members_content' ); ?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'No members were found.', 'buddypress' ); ?></p>
	</div>

<?php endif; 

				} else {

					/**
					 * Fires before the display of the group status message.
					 *
					 * @since 1.1.0
					 */
					do_action( 'bp_before_group_status_message' ); ?>

					<div id="message" class="info">
						<p><?php bp_group_status_message(); ?></p>
					</div>

					<?php

					/**
					 * Fires after the display of the group status message.
					 *
					 * @since 1.1.0
					 */
					do_action( 'bp_after_group_status_message' );

				}

			// Not looking at home
			else :

				// Group Admin
				if     ( bp_is_group_admin_page() ) : bp_get_template_part( 'groups/single/admin'        );

				// Group Activity
				elseif ( bp_is_group_activity()   ) : bp_get_template_part( 'groups/single/activity'     );

				// Group Members
				elseif ( bp_is_group_members()    ) : bp_groups_members_template_part();

				// Group Invitations
				elseif ( bp_is_group_invites()    ) : bp_get_template_part( 'groups/single/send-invites' );

				// Old group forums
				elseif ( bp_is_group_forum()      ) : bp_get_template_part( 'groups/single/forum'        );

				// Membership request
				elseif ( bp_is_group_membership_request() ) : bp_get_template_part( 'groups/single/request-membership' );

				// Anything else (plugins mostly)
				else                                : bp_get_template_part( 'groups/single/plugins'      );

				endif;

			endif;

		/**
		 * Fires after the display of the group home body.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_after_group_body' ); ?>

	</div><!-- #item-body -->

	<?php

	/**
	 * Fires after the display of the group home content.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_group_home_content' ); ?>

	<?php endwhile; endif; ?>

</div><!-- #buddypress -->
