<?php
namespace de\RaumZeitLabor\PartKeepr\Service;
declare(encoding = 'UTF-8');

interface RestfulService {
	public function get ();
	public function create ();
	public function update ();
	public function destroy ();
}