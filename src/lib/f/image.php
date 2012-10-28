<?php

/**
 * Przetwarzanie obrazkow 
 *
 * Obslugiwane formaty: gif, jpg, png.
 *
 *
 * Obecnie zmiana rozmiaru (metoda `resize`) i zmiana rozmiaru z wycinaniem (metoda `thumb`)
 *
 *
 * Ladowanie pliku odbywa sie wedlug typu zwracanego przez `getimagesize()[2]`.
 *
 *
 */
class f_image
{

    const TYPE_GIF = 'gif';
    const TYPE_JPG = 'jpg';
    const TYPE_PNG = 'png';

    const MIMETYPE_GIF = 'image/gif';
    const MIMETYPE_JPG = 'image/jpeg';
    const MIMETYPE_PNG = 'image/png';

    const ERROR_LOAD_NO_FILE          = 'ERROR_LOAD_NO_FILE';
    const ERROR_LOAD_UNSUPPORTED_TYPE = 'ERROR_LOAD_UNSUPPORTED_TYPE';
    const ERROR_LOAD_NOT_LOADED       = 'ERROR_LOAD_NOT_LOADED';
    const ERROR_SAVE_NOT_LOADED       = 'ERROR_SAVE_NOT_LOADED';
    const ERROR_SAVE_NOT_SAVED        = 'ERROR_SAVE_NOT_SAVED';
    const ERROR_SAVE_UNSUPPORTED_TYPE = 'ERROR_SAVE_UNSUPPORTED_TYPE';
    const ERROR_RENDER_NOT_LOADED     = 'ERROR_RENDER_NOT_LOADED';
    const ERROR_RESIZE_NOT_LOADED     = 'ERROR_RESIZE_NOT_LOADED';
    const ERROR_RESIZE                = 'ERROR_RESIZE';
    const ERROR_THUMB_NOT_LOADED      = 'ERROR_THUMB_NOT_LOADED';
    const ERROR_THUMB                 = 'ERROR_THUMB';

	protected $_resource;
	protected $_file;
	protected $_typeLoaded;
	protected $_type;
	protected $_error             = array();
    protected $_jpgQuality        = 90;
    protected $_renderSendsHeader = true;

    /* Object managment */

    /**
     * Statyczny konstruktor
     *
     * @param array $config
     * @return f_image
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }

	/**
     * Konstruktor
     * 
     * @param array $config 
     */
    public function __construct(array $config = array())
	{
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
	}

	/**
     * Destruktor
     *
	 * Niszczy zasob obrazu, zwalnia pamiec
	 */
	public function __destruct()
	{
		$this->destroy();
	}

    /**
     * Tworzy kopie 
     *
     * @return f_image Nowy obiekty z kopia zasobu
     */
	public function copy()
	{
        $copy = imagecreatetruecolor($this->width(), $this->height());
        imagecopy($copy, $this->resource(), 0, 0, 0, 0, $this->width(), $this->height());
        
		$image = new f_image();
		$image->resource($copy);
        $image->file($this->file());

		return $image;
	}

    /* Image properties */

    /**
     * Ustala/pobiera sciezke pliku obrazka
     *
     * @param type $sFile Sciezka do pliku
     * @return f_image
     */
    public function file($sFile = null)
    {
        // getter
        if (func_num_args() == 0) {
            return $this->_file;
        }

        // setter
        $this->_file = $sFile;
        return $this;
    }

    /**
     * Ustala/pobiera typ pliku
     *
     * @param const $tType Jedna ze stalych self::TYPE_*
     * @return string Jedna ze stalych self::TYPE_*
     * 
     */
    public function type($tType = null)
    {
        if (func_num_args() == 0) {
            return strlen($this->_type) > 0
                    ? $this->_type
                    : $this->_typeLoaded;
        }

        $this->_type = $tType;
        return $this;
    }

    /**
     * Zwraca szerokosc obrazu
     *
     * @return int
     */
	public function width()
	{
		return imagesx($this->_resource);
	}

    /**
     * Zwraca wysokosc obrazu
     *
     * @return type
     */
	public function height()
	{
		return imagesy($this->_resource);
	}

    /**
     *  Ustala/pobiera jakosc obrazu dla formatu jpg
     *
     * @param int $iJpgQuality Jakosc jpg <0,100>
     * @return int|this
     */
    public function jpgQuality($iJpgQuality = null)
    {
        if (func_num_args() == 0) {
            return $this->_jpgQuality;
        }

        $this->_jpgQuality = $iJpgQuality;
        return $this;
    }

    /* Image I/O - load, save, render */

    /**
     * Konwertuje rozszerzenie pliku do typu obrazu
     *
     * @param string $sFileExtension
     * @param const $tDefaultType jest wykorzystywany jezeli nie uda sie ustalic typu (self::TYPE_* )
     * @return const self::TYPE_*
     */
    public function extension2Type($sFileExtension, $tDefaultType = null)
    {
        switch ($sFileExtension) {

            case 'gif':
                return self::TYPE_GIF;

            case 'jpg':
            case 'jpeg':
            case 'jpe':
            case 'jif':
            case 'jfif':
            case 'jfi':
                return self::TYPE_JPG;

            case 'png':
                return self::TYPE_PNG;

            default:
                return $tDefaultType;

        }
    }


	/**
	 * Laduje obraz z pliku
	 *
	 * @param string $sFile Plik
	 * @return $this
	 */
	function load($sFile = null)
	{
        $this->_error = array();
        
        if (func_num_args() == 1) {
            $this->file($sFile);
        }

		if (!is_file($this->_file)) {
			$this->_error(self::ERROR_LOAD_NO_FILE);
			return $this;
		}

		list(,,$type) = getimagesize($this->_file);

		switch ($type) {

			case IMAGETYPE_GIF:
				$this->_resource   = imagecreatefromgif($this->_file);
                $this->_typeLoaded = self::TYPE_GIF;
				break;

			case IMAGETYPE_JPEG:
                $this->_resource   = imagecreatefromjpeg($this->_file);
                $this->_typeLoaded = self::TYPE_JPG;
				break;

			case IMAGETYPE_PNG:
				$this->_resource   = imagecreatefrompng($this->_file);
                $this->_typeLoaded = self::TYPE_PNG;
				break;

			default:
				$this->_error(self::ERROR_LOAD_UNSUPPORTED_TYPE);
                return $this;

		}

		if (!$this->_resource) {
            $this->_typeLoaded = null;
            $this->_error(self::ERROR_LOAD_NOT_LOADED);
		}

		return $this;
	}

	/**
	 * Zapisuje obraz jako plik
	 *
	 * @param string|null $sFile Plik
	 * @param integer $iJpgQuality
	 * @return $this
	 */
	function save($sFile = null)
	{

        // is resource loaded?
		if (!$this->_resource) {
			$this->_error(self::ERROR_SAVE_NOT_LOADED);
			return $this;
		}

        // file
        if ($sFile !== null) {
            $this->file($sFile);
        }

        $status = null;

		switch ($this->_resolveType()) {

			case self::TYPE_GIF:
				$status = imagegif($this->_resource, $this->_file);
				break;

			case self::TYPE_JPG:
                $status = imagejpeg($this->_resource, $this->_file, $this->_jpgQuality);
				break;

			case self::TYPE_PNG:
				$status = imagepng($this->_resource, $this->_file);
				break;

			default:
				$this->_error(self::ERROR_SAVE_UNSUPPORTED_TYPE);
				return $this;
        }

        if ($status === false) {
            $this->_error(self::ERROR_SAVE_NOT_SAVED);
        }

		return $this;
	}

    /**
     * Ustala/pobiera czy render* ma wysylac header `Content-Type`
     *
     * @param boolean $bRenderSendsHeader
     * @return this
     */
    public function renderSendsHeader($bRenderSendsHeader = null)
    {
        if (func_num_args() == 0) {
            return $this->_renderSendsHeader;
        }

        $this->_renderSendsHeader = $bRenderSendsHeader;
        return $this;
    }

	public function render($tType = null)
	{

        // is resource loaded?
		if (!$this->_resource) {
			$this->_error(self::ERROR_RENDER_NOT_LOADED);
			return $this;
		}

        // type
        $type = $this->_resolveType($tType);

        // header Content-Type
        if ($this->_renderSendsHeader) {
            switch ($type) {
                case self::TYPE_GIF:
                    $mime = self::MIMETYPE_GIF;
                    break;
                case self::TYPE_JPG:
                    $mime = self::MIMETYPE_JPG;
                    break;
                case self::TYPE_PNG:
                    $mime = self::MIMETYPE_PNG;
                    break;
            }
            header('Content-Type: ' . $mime);
        }

        // render
        switch ($type) {

            case self::TYPE_GIF:
                imagegif($this->_resource);
                break;

            case self::TYPE_JPG:
                imagejpeg($this->_resource, null, $this->_jpgQuality);
                break;

            case self::TYPE_PNG:
                imagepng($this->_resource);
                break;

        }

		return $this;
	}


	public function renderGif()
	{
        return $this->render(self::TYPE_GIF);
	}

	public function renderJpg()
	{
        return $this->render(self::TYPE_JPG);
	}

	public function renderPng()
	{
        return $this->render(self::TYPE_PNG);
	}

    /**
	 * Ustala/pobiera zasob obrazu
	 *
	 * @param resource $rImageResourceIdentifier
	 * @return $this|resource
	 */
	public function resource($rImageResourceIdentifier = null)
	{
        if (func_num_args() == 0) {
            return $this->_resource;
        }

        $this->_error    = array();
		$this->_resource = $rImageResourceIdentifier;
		return $this;
	}

	/**
	 * Niszczy zasób obrazu, zwalnia pamięć
	 *
	 * @return $this
	 */
	public function destroy()
	{
		if (!$this->_resource) {
			return $this;
		}
        
		imagedestroy($this->_resource);
		$this->_resource = null;
        $this->_error    = array();
		return $this;
	}

    /* Errors */

	/**
	 * Pobiera tablice napotkanych błędów
	 *
	 * @return array
	 */
	public function error()
	{
		return $this->_error;
	}

    /* Image processing */

	function resize($iNewWidth, $iNewHeight, $bExtend = true)
	{

		if (!$this->_resource) {
			$this->_error(self::ERROR_RESIZE_NOT_LOADED);
			return $this;
		}

		if ($iNewWidth <= 0) {
            throw new f_image_exception_invalidArgument("Szerokosc nowego obrazu musi byc wieksza od zera.");
		}

		if ($iNewHeight <= 0) {
            throw new f_image_exception_invalidArgument("Wysokosc nowego obrazu musi byc wieksza od zera.");
		}

		if ($bExtend === false) {
			if ($this->width() <= $iNewWidth && $this->height() <= $iNewHeight) {
				return $this;
			}
		}
		$iWidth  = $iNewWidth;
		$iHeight = (int)($iWidth * $this->height() / $this->width());
		if ($iHeight > $iNewHeight) {
			$iHeight = $iNewHeight;
			$iWidth  = (int)($iHeight * $this->width() / $this->height());
		}
		if (!$rImage = imagecreatetruecolor($iWidth, $iHeight)) {
			$this->_error(self::ERROR_RESIZE);
			return $this;
		}
		if (!imagecopyresampled($rImage, $this->_resource, 0, 0, 0, 0, $iWidth, $iHeight, $this->width(), $this->height())) {
			$this->_error(self::ERROR_RESIZE);
			return $this;
		}
		$this->_resource  = $rImage;
		return $this;
	}

	function thumb($iNewWidth, $iNewHeight = null)
	{
		if (!$this->_resource) {
			$this->_error(self::ERROR_THUMB_NOT_LOADED);
			return $this;
		}

		if ($iNewHeight === null) {
			$iNewHeight = $iNewWidth;
		}

		if ($iNewWidth <= 0) {
            throw new f_image_exception_invalidArgument("Szerokosc nowego obrazu musi byc wieksza od zera.");
		}

		if ($iNewHeight <= 0) {
            throw new f_image_exception_invalidArgument("Wysokosc nowego obrazu musi byc wieksza od zera.");
		}

		$iX = 0;
		$iY = 0;
		$iWidth = $iNewWidth;
		$iHeight = (int)($iWidth * $this->height() / $this->width());
		if ($iHeight <= $iNewHeight) {
			$iHeight = $iNewHeight;
			$iWidth = (int)($iHeight * $this->width() / $this->height());
			$iX = (int) ($iWidth - $iNewWidth) / 2;
		}
		else {
			$iY = (int) ($iHeight - $iNewHeight) / 2;
		}
		if (!$rImage = imagecreatetruecolor($iWidth, $iHeight)) {
			$this->_error(self::ERROR_THUMB);
			return $this;
		}
		if (!imagecopyresampled($rImage, $this->_resource, 0, 0, 0, 0, $iWidth, $iHeight, $this->width(), $this->height())) {
			$this->_error(self::ERROR_THUMB);
			return $this;
		}
		if (!$rImage2 = imagecreatetruecolor($iNewWidth, $iNewHeight)) {
			$this->_error(self::ERROR_THUMB);
			return $this;
		}
		if (!imagecopy($rImage2, $rImage, 0, 0, $iX, $iY,  $iNewWidth, $iNewHeight)) {
			$this->_error(self::ERROR_THUMB);
			return $this;
		}
		$this->_resource  = $rImage2;
		return $this;
	}

    /* Private api */

	protected function _error($tError)
	{
		$this->_error[] = $tError;
	}

    protected function _resolveType($tDefaultType = null)
    {
        
        // 1. Podany przez metode `type`
        if ($this->_type !== null) {
            return $this->_type;
        }

        // 2. Wedlug rozszerzenia
        $type = $this->extension2Type(strtolower(end(explode('.', $this->_file)))); // wedlug rozszerzenia
        if ($type !== null) {
            return $type;
        }

        // 3. Wedlug typu zaladowanego pliku
        if ($this->_typeLoaded !== null) {
            return $this->_typeLoaded;
        }

        // 4. Standardowy z argumentu
        if ($tDefaultType !== null) {
            return $tDefaultType;
        }

        // 5. Super standardowy np. jezeli zaladujemy obraz przez `resource` nie podajac `type`
        return self::TYPE_JPG;

    }

}