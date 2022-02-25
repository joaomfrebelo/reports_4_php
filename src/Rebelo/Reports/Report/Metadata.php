<?php

/*
 * The MIT License
 *
 * Copyright 2020 João Rebelo.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

declare(strict_types=1);

namespace Rebelo\Reports\Report;

/**
 * Documents metadata.
 * Only available in API
 * @since 3.0.0
 */
class Metadata implements IAReport
{

    /**
     * API node
     * @since 3.0.0
     */
    const API_N_METADATA = "metadata";

    /**
     * API property
     * @since 3.0.0
     */
    const API_P_TITLE = "title";

    /**
     * API property
     * @since 3.0.0
     */
    const API_P_AUTHOR = "author";

    /**
     * API property
     * @since 3.0.0
     */
    const API_P_SUBJECT = "subject";

    /**
     * API property
     * @since 3.0.0
     */
    const API_P_KEYWORDS = "keywords";

    /**
     * API property
     * @since 3.0.0
     */
    const API_P_APPLICATION = "application";

    /**
     * API property
     * @since 3.0.0
     */
    const API_P_CREATOR = "creator";

    /**
     * API property
     * @since 3.0.0
     */
    const API_P_DISPLAY_METADATA_TITLE = "displayMetadataTitle";

    /**
     * @var \Logger
     * @since 3.0.0
     */
    private \Logger $logger;

    /**
     * The document title
     * @since 3.0.0
     */
    private string $title;

    /**
     * The document author
     * @since 3.0.0
     */
    private string $author;

    /**
     * The document subject
     * @since 3.0.0
     */
    private string $subject;

    /**
     * Document keywords
     * @since 3.0.0
     */
    private string $keywords;

    /**
     * Application
     * @since 3.0.0
     */
    private string $application;

    /**
     * The document creator
     * @since 3.0.0
     */
    private string $creator;

    /**
     * Display meta title
     * @since 3.0.0
     */
    private bool $displayMetadataTitle = false;

    /**
     * Documents metadata
     * @since 3.0.0
     */
    public function __construct()
    {
        $this->logger = \Logger::getLogger(\get_class($this));
        $this->logger->debug(\sprintf("New instance of '%s'", \get_class($this)));
    }

    /**
     * @return string
     * @since 3.0.0
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set title
     * @param string $title
     * @return \Rebelo\Reports\Report\Metadata
     * @since 3.0.0
     */
    public function setTitle(string $title): Metadata
    {
        $this->title = $title;
        $this->logger->debug(\sprintf("Title set to '%s'", $this->title));
        return $this;
    }

    /**
     * Get author
     * @return string
     * @since 3.0.0
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Set author
     * @param string $author
     * @return \Rebelo\Reports\Report\Metadata
     * @since 3.0.0
     */
    public function setAuthor(string $author): Metadata
    {
        $this->author = $author;
        $this->logger->debug(\sprintf("Author set to '%s'", $this->author));
        return $this;
    }

    /**
     * Get subject
     * @return string
     * @since 3.0.0
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * Set subject
     * @param string $subject
     * @return \Rebelo\Reports\Report\Metadata
     * @since 3.0.0
     */
    public function setSubject(string $subject): Metadata
    {
        $this->subject = $subject;
        $this->logger->debug(\sprintf("Subject set to '%s'", $this->subject));
        return $this;
    }

    /**
     * Get keywords
     * @return string
     * @since 3.0.0
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * Set keywords
     * @param string $keywords
     * @return \Rebelo\Reports\Report\Metadata
     * @since 3.0.0
     */
    public function setKeywords(string $keywords): Metadata
    {
        $this->keywords = $keywords;
        $this->logger->debug(\sprintf("Keywords set to '%s'", $this->keywords));
        return $this;
    }

    /**
     * Get application
     * @return string
     * @since 3.0.0
     */
    public function getApplication(): string
    {
        return $this->application;
    }

    /**
     * Set application
     * @param string $application
     * @return \Rebelo\Reports\Report\Metadata
     * @since 3.0.0
     */
    public function setApplication(string $application): Metadata
    {
        $this->application = $application;
        $this->logger->debug(\sprintf("Application set to '%s'", $this->application));
        return $this;
    }

    /**
     * Get creator
     * @return string
     * @since 3.0.0
     */
    public function getCreator(): string
    {
        return $this->creator;
    }

    /**
     * Set creator
     * @param string $creator
     * @return \Rebelo\Reports\Report\Metadata
     * @since 3.0.0
     */
    public function setCreator(string $creator): Metadata
    {
        $this->creator = $creator;
        $this->logger->debug(\sprintf("Creator set to '%s'", $this->creator));
        return $this;
    }

    /**
     * Get isDisplayMetadataTitle
     * @return bool
     * @since 3.0.0
     */
    public function isDisplayMetadataTitle(): bool
    {
        return $this->displayMetadataTitle;
    }

    /**
     * Set isDisplayMetadataTitle
     * @param bool $displayMetadataTitle
     * @return \Rebelo\Reports\Report\Metadata
     * @since 3.0.0
     */
    public function setDisplayMetadataTitle(bool $displayMetadataTitle): Metadata
    {
        $this->displayMetadataTitle = $displayMetadataTitle;
        $this->logger->debug(
            \sprintf("DisplayMetadataTitle set to '%s'", $this->displayMetadataTitle ? "true" : "false")
        );
        return $this;
    }

    /**
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\Reports\Report\ReportException
     * @since 3.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        throw new ReportException("Not implemented");
    }

    /**
     * @param array $data
     * @return void
     * @since 3.0.0
     */
    public function fillApiRequest(array &$data): void
    {
        $data[static::API_N_METADATA] = [];
        $md = &$data[static::API_N_METADATA];

        if (isset($this->title)) {
            $md[static::API_P_TITLE] = $this->getTitle();
        }

        if (isset($this->author)) {
            $md[static::API_P_AUTHOR] = $this->getAuthor();
        }

        if (isset($this->subject)) {
            $md[static::API_P_SUBJECT] = $this->getSubject();
        }

        if (isset($this->keywords)) {
            $md[static::API_P_KEYWORDS] = $this->getKeywords();
        }

        if (isset($this->application)) {
            $md[static::API_P_APPLICATION] = $this->getApplication();
        }

        if (isset($this->creator)) {
            $md[static::API_P_CREATOR] = $this->getCreator();
        }

        $md[static::API_P_DISPLAY_METADATA_TITLE] = $this->isDisplayMetadataTitle();
    }

    /**
     * @return string
     * @since 3.0.0
     */
    public function __toString()
    {
        return \serialize($this);
    }
}
