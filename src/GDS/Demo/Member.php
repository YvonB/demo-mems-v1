<?php
/**
 * Membre pour GDS
 *
 * @author Yvon Benahita
 */

namespace GDS\Demo;

use GDS\Schema;
use GDS\Store;

class Member
{

    /**
     * Instance de Memcache
     *
     * @var \Memcached|null
     */
    private $obj_cache = NULL;

    /**
     * Instance du magasin GDS
     *
     * @var Store|null
     */
    private $obj_store = NULL;

    /**
     * @return \Memcached|null
     */
    private function getCache()
    {
        if(NULL === $this->obj_cache) {
            $this->obj_cache = new \Memcached();
        }
        return $this->obj_cache;
    }

    /**
     * Prendre les valeurs insérées les plus récentes. 
     *
     * @return array
     */
    public function getRecentMember()
    {
        $arr_posts = $this->getCache()->get('recent');
        if(is_array($arr_posts)) {
            return $arr_posts;
        } else {
            return $this->updateCache();
        }
    }

    /**
     * Mettre à jour le cache de Datastore
     *
     * @return array
     */
    private function updateCache()
    {
        $obj_store = $this->getStore();
        $arr_posts = $obj_store->query("SELECT * FROM EspaceMembre ORDER BY posted DESC")->fetchPage(POST_LIMIT);
        $this->getCache()->set('recent', $arr_posts);
        return $arr_posts;
    }

    /**
     * Insèrez l'entité
     *
     * @param $str_nom
     * @param $str_mail
     * @param $str_mdp
     * @param $str_ip
     */
    public function createMember($str_nom, $str_mail, $str_mdp, $str_ip)
    {
        $obj_store = $this->getStore();
        $obj_store->upsert($obj_store->createEntity([
            'posted' => date('Y-m-d H:i:s'),
            'nom' => $str_nom,
            'mail' => $str_mail,
            'mdp' => $str_mdp,
            'ip' => $str_ip
        ]));

        // Mettre à jour le cache
        $this->updateCache();
    }

    /**
     * Configuration et retourner un magasin
     *
     * @return Store
     */
    private function getStore()
    {
        if(NULL === $this->obj_store) {
            $this->obj_store = new Store($this->makeSchema());
        }
        return $this->obj_store;
    }

    /**
     * Créez un schéma pour les entrées 
     *
     * 'posted' est 'heure de la date d'entrée des valeur 
     *
     * @return Schema
     */
    private function makeSchema()
    {
        return (new Schema('EspaceMembre'))
            ->addDatetime('posted')
            ->addString('nom', FALSE)
            ->addString('mail', FALSE)
            ->addString('mdp', FALSE)
            ->addString('ip')
        ;
    }

}