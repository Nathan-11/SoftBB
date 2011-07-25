<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page de notification simple (affichage)
 *   Version : 1.x
 *   
 *   Copyright            : (C) 2005-201x - Équipe SoftBB.net
 *   Site-web             : http://www.softbb.net/
 *   Em@il                : Voir sur le site
 *   Développement        : Equipe SoftBB - ouverte - (voir sur le site)
 *
 *   Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou 
 *   le modifier au titre des clauses de la Licence Publique Générale GNU.
 *   Plus d'infos sur /index.php
 *
 ***************************************************************************/

if(!defined('IN_SOFTBB')) exit('Not in SoftBB');
$bdd = null;

// détermine la notif (suppression, déplacement, enregistrement, etc...)
if(isset($_GET['aff']))	
{
	// Déconnection
	if($_GET['aff'] == 'lgout')
	{
		display_error($langue_notifs['lgo1'], '
			<p>'.$langue_notifs['lgo2'].'</p> 
			<p>
				<a href="'.((!$url_rewriting) ? 'index.php' : 'indexforum.html').'">'.$langue_notifs['lgo3'].'</a> || 
				<a href="logout.php">'.$langue_notifs['lgo4'].'</a>
			</p>');
	}
	
	// Sauvegarde de profil
	else if($_GET['aff'] == 'profsave'){		
		display_error($langue_notifs['pfs1'], '
			<p>'.$langue_notifs['pfs2'].'</p>
			<p>
				<a href="'.((!$url_rewriting) ? 'index.php' : 'index.html').'">
					&gt;&gt; '.$langue_notifs['pfs2'].' &lt;&lt;
				</a>
			</p>');
	}
	
	// Fin de l'inscription
	else if($_GET['aff'] == 'regok2'){
		display_error(
			$langue_notifs['rok1'],
			$langue_notifs['rok2']
		);
	}
	
	// Suppression de sujet
	else if($_GET['aff'] == 'delvalid2' && isset($_GET['id2']) && is_numeric($_GET['id2']) && $_GET['id2'] >= 0)
	{
		display_error($langue_notifs['dels1'], '
			<p>'.$langue_notifs['dels2'].'</p>
			<p>
				<a href="'.((!$url_rewriting) ? 'index.php?page=forum&idf='.$_GET['id2'] : 'forum-'.$_GET['id2'].'.html').'">
					&gt;&gt; '.$langue_notifs['dels3'].' &lt;&lt;
				</a>
			</p>');
	}
	
	// Suppression de réponse
	else if($_GET['aff'] == 'delvalid' && isset($_GET['id2']))
	{
		display_error($langue_notifs['delr1'], '
			<p>'.$langue_notifs['delr2'].'</p>
			<p>
				<a href="'.((!$url_rewriting) ? 'index.php?page=post&ids'.$_GET['id2'] : 'post-'.$_GET['id2'].'.html').'">
					&gt;&gt; '.$langue_notifs['delr3'].' &lt;&lt;
				</a>
			</p>');
	}
	
	// Synchronisation du nombre de messages d'un sujet
	else if($_GET['aff'] == 'resynchok' && isset($_GET['ids'])){
		display_error($langue_notifs['syn1'], '
			<span class="titreforumend">'.$langue_notifs['syn2'].'</span>
			<p>
				<a href="'.((!$url_rewriting) ? 'index.php?page=post&amp;ids='.$_GET['ids'] : 'post-'.$_GET['ids'].'.html').'">
					&gt;&gt; '.$langue_notifs['syn3'].' &lt;&lt;
				</a>
			</p>');
	}
	// fermeture du forum
	else if($_GET['aff'] == 'lock'){
		display_error($langue_notifs['lock1'], nl2br($message_de_lock));
	}

	// ERREUR sinon
	else
	{
		display_error($global_lang['err1'], '<p>'.$global_lang['err2'] . '</p> 
				<p>
					<a href="'. ((!$url_rewriting) ? 'index.php' : 'indexforum.html').'">
						&gt;&gt; '.$global_lang['err3'].'&lt;&lt;
					</a>
				</p>');
	}
} 
// ERREUR sinon
else
{
	display_error($global_lang['err1'], '<p>'.$global_lang['err2'] . '</p> 
			<p>
				<a href="'. ((!$url_rewriting) ? 'index.php' : 'indexforum.html').'">
					&gt;&gt; '.$global_lang['err3'].'&lt;&lt;
				</a>
			</p>');
}

?>
