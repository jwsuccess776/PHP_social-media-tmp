<?php
/**
 * ����� ��������� ������������ ������������ ������ � ����������� ��������.
 *
 * ��� ������ {@link lock()} ��������� ���������� �����, ���� ������ �����, � ������������ ��� {@link release()}
 * ������ ����� ������ ����������� (��� ���� ����������� ����� ����������).
 *
 * ���������� ���� (lock-����) ��� ���������� ��������� ����������� �� ����� ����������
 * ��������. ������ ���� ���� � ����� /tmp. ����� ������� ������ ����� � ������������.
 *
 * @package core
 */
class PaLock {

    /**
     * ���� � �����, ��� �������� ����� ����������.
     *
     * @var string
     * @access private
     */
    var $tmpPath;

    /**
     * ��� ������� ����������.
     *
     * @var string
     * @access private
     */
    var $name;

    /**
     * ������ ���� � ����� ��� ����������.
     *
     * @var string
     * @access private
     */
    var $lockFile;

    /**
     * ������������������ �� ������.
     *
     * @var boolean
     * @access private
     */
    var $isInitialized = false;

    /**
     * Handler of the locked file.
     *
     * @var resource
     * @access private
     */
    var $hFile = null;

    /**
     * ������ ���������� ���� � ���������:
     * <ul>
     * <li>������� ������������ ������;</li>
     * <li>��������� ��������� ���� (���� ��� ���).</li>
     * </ul>
     *
     * @param string $path
     * @param boolean $finalSlash ������ ��� ��� ���� ��������� ���� � ����� ����
     * @return string
     * @access public
     */
    function normalizeStoragePath($path, $finalSlash = true)
    {
        $path = preg_replace("`/+`", "/", $path);
        $path = preg_replace("`/$`", "", $path);
        if ($finalSlash && strlen($path) > 0 && $path[strlen($path)-1] != "/") {
            $path .= "/";
        }

        return $path;
    }

    /**
     * ������� ������ ���������� � ������ $name.
     *
     * @param string $name
     * @param string $tmpPath �����, ��� �������� ����� ����������.
     */
    function PaLock($name, $tmpPath = "/tmp")
    {
        $this->name = $name;
        $this->tmpPath = $this->normalizeStoragePath($tmpPath, false);
    }

    /**
     * ���������������� ������.
     *
     * ���������� PaError � ������ ������ ������� � /tmp.
     *
     * @access private
     */
    function init()
    {
        if (!$this->isInitialized) {
            if (!is_writable($this->tmpPath)) {
                die("Can't write to $this->tmpPath.". __FILE__. __LINE__);
            }

            $this->lockFile = $this->tmpPath."/#lock".$this->name;

            $this->isInitialized = true;
        }
    }

    /**
     * �������� �������� ������ � ������������ �������.
     *
     * ���� ������ ����� �� �� �������, ���� ������ �� ����������� ���� �� ���������
     * ������.
     *
     * @param boolean $notBlocking ������ �� ���������� ����������� ����� � ������ ��
     * ����������� � ������������� ���������.
     * @return boolean ������ �� ���������� �������.
     * @access public
     */
    function lock($notBlocking = false)
    {
        $this->init();

        $this->hFile = @fopen($this->lockFile, "a+");
        if ($this->hFile === false) {
            return false;
        }
        $res = @flock($this->hFile, LOCK_EX | ($notBlocking ? 0 : LOCK_NB));
        if ($res === false) {
            @fclose($this->hFile);
            $this->hFile = null;
            return false;
        }

        return true;
    }

    /**
     * ���������� ��������������� ������.
     *
     * @return boolean ������ �� ������������� �������.
     * @access public
     */
    function release()
    {
        $this->init();

        if ($this->hFile === null) {
            return false;
        }

        $this->hFile = @fopen($this->lockFile, "a+");
        if ($this->hFile === false) {
            return false;
        }
        $res = @flock($this->hFile, LOCK_UN);
        if ($res === false) {
            @fclose($this->hFile);
            $this->hFile = null;
            return false;
        }

        @fclose($this->hFile);
        $this->hFile = null;

        return true;
    }
}
?>