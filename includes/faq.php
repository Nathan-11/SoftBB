<?php

/***************************************************************************
 *
 *   SoftBB - Forum de discussion - Page d'aide : FAQ
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
 
if(!defined('IN_SOFTBB')) 
	exit('Not in SoftBB');
$bdd = null; 

?>
<div class="top_infos"><?php echo '
	<a href="' . ((!$url_rewriting) ? 'index.php' : 'index.html' ) .'">
		'.$langue_index['index21'].' '.htmlentities($nomduforum).'
	</a>'; ?>
</div>
<table class="texte_base_normal" width="100%" cellspacing="0" cellpadding="0">
	<tr class="titreforum">
		<td class="titreforumstart texte_base_titrespec">Faq</td>
	</tr>
	<tr>
		<td class="cadre1_bas" style="padding:30px">
		<?php
		for($si=0 ; $si<count($langue_faq_titre) ; $si++)
		{
			echo '
				<div class="faq_section">'.$langue_faq_titre['faq_titre'.($si+1).''].'</div>';
			for($si2=0;$si2!=(count($langue_faq[$si+1])/2);$si2++)
			{
				echo'
				<ul>';
				echo'
					<li class="q">'.$langue_faq[$si+1]['faq_question'.($si2+1)].'</li>';
				echo'
					<li>'.$langue_faq[$si+1]['faq_reponse'.($si2+1)].'</li>';
				echo'
				</ul>';

			}
			echo'<br />';
		}
		?>
		</td>
	</tr>
</table>          
