<?php do_action( 'bp_docs_before_doc_header' ) ?>

<?php /* Subnavigation on user pages is handled by BP's core functions */ ?>
<?php if ( ! bp_is_user() ): ?>
	<div class="item-list-tabs no-ajax" id="subnav" role="navigation">
		<?php bp_docs_tabs( current_user_can( 'bp_docs_create' ) ); ?>
	</div><!-- .item-list-tabs -->
<?php endif ?>

<h4> 
	<?php
		$dbhost = 'localhost:3306';
		$dbuser = 'root';
		$dbpass = 'admin';
		$dbname = 'wordpress';

		$conn = mysql_connect($dbhost, $dbuser, $dbpass);
		mysql_select_db('wordpress');
		$current_user = wp_get_current_user(); 
		$current_username = $current_user->user_login;
		$sqlgetrole = "SELECT role FROM wp_group_user_roles WHERE username='$current_username'";
		$retvalgetrole = mysql_query( $sqlgetrole, $conn );
		$row_getrole = mysql_fetch_assoc($retvalgetrole);
		$existrole = $row_getrole["role"];
		if($existrole == ""){
			echo "Please select a role on <a href='../home'>Home</a> page before editing docs";
		}else if($existrole == "Idea"){
			echo "Your current role is : $existrole; Please create a new document and write your ideas on the topic";
		}else if($existrole == "Writer"){
			echo "Your current role is : $existrole; Please collaborate with Idea Generator and see if he has put in all his ideas and start writing/building on those ideas";
		}else if($existrole == "Editor"){
			echo "Your current role is : $existrole; Please collaborate with Writer and see if he has elaborated on all the ideas and start editing the document";
		}else{
			echo "Your current role is : $existrole; Please collaborate with Editor and see if editing has been completed and proof read the document, check the references and finalize.
			      Let your teammates know after you are done";
		}

		mysql_close($conn);

	?>
</h4>

<h4> <font color="green">
	<a href="../group-chat">Chat</a> with your teammates who are online now: 
	<?php
		$dbhost = 'localhost:3306';
		$dbuser = 'root';
		$dbpass = 'admin';
		$dbname = 'wordpress';

		$conn = mysql_connect($dbhost, $dbuser, $dbpass);
		mysql_select_db('wordpress');
		$current_user = wp_get_current_user(); 
		$current_username = $current_user->user_login;
		$groupname = bp_get_current_group_slug();
		$sqlgetusr = "SELECT username FROM wp_login_check WHERE loggedin=1";
		$retvalgetusr = mysql_query( $sqlgetusr, $conn );

		$sqlgrp = "SELECT username FROM wp_group_user_roles WHERE groupname ='$groupname'";
		$retgrp = mysql_query( $sqlgrp, $conn );

		if(! $retgrp || ! $retvalgetusr)
		{
		  die('Could not get data: ' . mysql_error());
		}
		while($row_grp = mysql_fetch_assoc($retgrp))
		{
			while( $row_getusr = mysql_fetch_assoc($retvalgetusr)){
				if($row_getusr['username'] != $current_username && $row_getusr['username'] == $row_grp['username']){
					echo "{$row_getusr['username']};";
				}
		    	
		    }		         
		}
		

		mysql_close($conn);

	?>
</font>
</h4>

<?php do_action( 'bp_docs_before_doc_header_content' ) ?>

<?php if ( bp_docs_is_existing_doc() ) : ?>

	<div id="bp-docs-single-doc-header">
		<?php if ( ! bp_docs_is_theme_compat_active() ) : ?>
			<h2 class="doc-title"><?php bp_docs_the_breadcrumb() ?><?php if ( bp_docs_is_doc_trashed() ) : ?> <span class="bp-docs-trashed-doc-notice" title="<?php esc_html_e( 'This Doc is in the Trash', 'bp-docs' ) ?>">Trash</span><?php endif ?></h2>
		<?php endif ?>

		<?php do_action( 'bp_docs_single_doc_header_fields' ) ?>
	</div>

	<div class="doc-tabs">
		<ul>
			<li<?php if ( bp_docs_is_doc_read() ) : ?> class="current"<?php endif ?>>
				<a href="<?php bp_docs_doc_link() ?>"><?php _e( 'Read', 'bp-docs' ) ?></a>
			</li>

			<?php if ( current_user_can( 'bp_docs_edit' ) ) : ?>
				<li<?php if ( bp_docs_is_doc_edit() ) : ?> class="current"<?php endif ?>>
					<a href="<?php bp_docs_doc_edit_link() ?>"><?php _e( 'Edit', 'bp-docs' ) ?></a>
				</li>
			<?php endif ?>

			<?php do_action( 'bp_docs_header_tabs' ) ?>
		</ul>
	</div>

<?php elseif ( bp_docs_is_doc_create() ) : ?>

	<h2><?php _e( 'New Doc', 'bp-docs' ); ?></h2>

<?php endif ?>

<?php do_action( 'bp_docs_after_doc_header_content' ) ?>
